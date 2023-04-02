<?php
include_once __DIR__ . '/../BaseRepository.php';

class EventRepository extends BaseRepository
{
    public function getEvents()
    {
        $result = executeQuery(
            $this->con,
            "SELECT events.id, start_date, end_date, category_id, name, title, description, base64String
            FROM events
            ORDER BY start_date DESC"
        );

        $json = mysqli_fetch_all ($result, MYSQLI_ASSOC);

        return $json;    }

    public function addEvent($name, $title, $description, $startDate, $endDate, $categoryId, $imageFile)
    {
        if ($endDate != null && $startDate > $endDate) {
            throw new Exception('Start date cannot be after end date!');
        }

        $resizedImage = $this->getResizedImageBase64($imageFile, 200);

        executeQueryWithParams(
            $this->con,
            "INSERT INTO events (name, title, description, start_date, end_date, category_id, base64String) 
            VALUES (?,?,?,?,?,?,?)",
            'sssssss',
            $name,
            $title,
            $description,
            $startDate,
            $endDate != null ? $endDate : NULL,
            $categoryId,
            $resizedImage
        );
    }

    public function editEvent($id, $name, $title, $description, $startDate, $endDate, $categoryId, $imageFile)
    {
        if ($endDate != null && $startDate > $endDate) {
            throw new Exception('Start date cannot be after end date!');
        }

        $resizedImage = $imageFile != null ? $this->getResizedImageBase64($imageFile, 200) : null;

        executeQueryWithParams(
            $this->con,
            "UPDATE events 
            SET name = ?, title = ?, description = ?, start_date = ?, end_date = ?, category_id = ?, base64String = ?
            WHERE id = ?",
            'ssssssss',
            $name,
            $title,
            $description,
            $startDate,
            $endDate != null ? $endDate : NULL,
            $categoryId,
            $resizedImage,
            $id
        );
    }

    public function getResizedImageBase64($imageData, $newHeight)
    {
        $data = explode(',', $imageData);
        $decodedSource = base64_decode($data[1]);

        $im = imagecreatefromstring($decodedSource);
        $source_width = imagesx($im);
        $source_height = imagesy($im);
        $ratio =  $source_width / $source_height;

        $new_height = $newHeight;
        $new_width = $ratio * $newHeight;

        $resizedImage = imagescale($im, $new_width, $new_height);

        ob_start();

        imagejpeg($resizedImage);
        $image_data = ob_get_contents();

        ob_end_clean();

        $image_data_base64 = base64_encode($image_data);

        $dataType = $data[0];

        return "{$dataType},{$image_data_base64}";
    }

    public function deleteEvent($id)
    {
        executeQueryWithParams(
            $this->con,
            "DELETE FROM events 
            WHERE id = ?",
            's',
            $id
        );
    }
}
