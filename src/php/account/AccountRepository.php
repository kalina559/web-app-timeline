<?php
include_once __DIR__.'/../BaseRepository.php';

class AccountRepository extends BaseRepository
{
    public function login($login, $password)
    {
        $hashedPassword = hash('sha256', $password);

        $result = executeQueryWithParams(
            $this->con,
            "SELECT id FROM users WHERE login = ? AND password = ?",
            'ss',
            $login,
            $hashedPassword
        );

        if ($result->num_rows === 1) {
            $row = $result->fetch_row();
            $userId = $row[0] ?? false;

            $this->clearExpiredUserLogins($userId);
            $this->markUserAsLoggedIn($userId);

            return TRUE;
        }
        return FALSE;
    }

    public function markUserAsLoggedIn($userId)
    {
        $session_id = session_id();

        $now = new DateTime();
        $now->add(new DateInterval('PT' . LOGIN_SESSION_DURATION_MINUTES . 'M'));

        executeQueryWithParams(
            $this->con,
            "INSERT INTO  users_logged_in (user_id, session_id, valid_until) VALUES (?, ?, ?)",
            'sss',
            $userId,
            $session_id,
            $now->format('Y-m-d H:i:s')
        );
    }

    
    public function clearExpiredUserLogins()
    {
        $now = new DateTime();
        $session_id = session_id();

        executeQueryWithParams(
            $this->con,
            "DELETE FROM  users_logged_in WHERE valid_until < ? OR session_id = ?",
            'ss',
            $now->format('Y-m-d H:i:s'),
            $session_id
        );
    }

    public function logout()
    {
        $session_id = session_id();

        executeQueryWithParams(
            $this->con,
            "DELETE FROM users_logged_in WHERE session_id = ?",
            's',
            $session_id
        );
    }

    public function userIsLoggedIn()
    {
        $session_id = session_id();

        $result = executeQueryWithParams(
            $this->con,
            "SELECT * FROM users_logged_in WHERE session_id = ?",
            's',
            $session_id
        );

        if ($result->num_rows > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function getLoggedInUser()
    {
        $session_id = session_id();
        $now = new DateTime();

        $result = executeQueryWithParams(
            $this->con,
            "SELECT login FROM users_logged_in
            JOIN users ON users_logged_in.user_id = users.id
            WHERE session_id = ? AND valid_until > ?",
            'ss',
            $session_id,
            $now->format('Y-m-d H:i:s')
        );

        if ($result->num_rows > 0) {
            $row = $result->fetch_row();
            $login = $row[0] ?? false;
            return array($login);
        } else {
            return NULL;
        }
    }

    public function updateUsersPassword($oldPassword, $newPassword)
    {
        $currentUser = $this->getCurrentUser();
        if ($currentUser->num_rows == 1) {
            $row = $currentUser->fetch_row();
            $userId = $row[0];
            $isOldPasswordCorrect = $this->validateOldPassword($userId, $oldPassword);

            if ($isOldPasswordCorrect->num_rows == 1) {
                $row = $isOldPasswordCorrect->fetch_row();
                $this->updatePassword($userId, $newPassword);
                return "success";
            } else {
                throw new Exception("The current password provided was incorrect.");
            }
        } else {
            throw new Exception("Couldn't find the current user in the database.");
        }
    }

    public function getCurrentUser()
    {
        $session_id = session_id();

        return executeQueryWithParams(
            $this->con,
            "SELECT user_id FROM users_logged_in
            WHERE session_id = ?",
            's',
            $session_id
        );
    }

    public function validateOldPassword($userId, $oldPassword)
    {
        $hashedOldPassword = hash('sha256', $oldPassword);

        // check if the provided old password is correct
        return executeQueryWithParams(
            $this->con,
            "SELECT id FROM users
        WHERE id = ? AND password = ?",
            'ss',
            $userId,
            $hashedOldPassword
        );
    }

    public function updatePassword($userId, $newPassword)
    {
        $hashedNewPassword = hash('sha256', $newPassword);

        // update the password
        executeQueryWithParams(
            $this->con,
            "UPDATE users
            SET password = ?
            WHERE id = ?",
            'ss',
            $hashedNewPassword,
            $userId
        );
    }
}
