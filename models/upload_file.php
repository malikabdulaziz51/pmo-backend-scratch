<?php

class Upload
{
    public $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function upload_file()
    {
        if (isset($_FILES['file'])) {
            $errors = [];
            $path = '/app/pmo-backend/uploads/';

            // $all_files = count($_FILES['files']['tmp_name']);
            // for ($i = 0; $i < $all_files; $i++) {
            $file_name = $_FILES['file']['name'];
            $file_tmp = $_FILES['file']['tmp_name'];
            $file_type = $_FILES['file']['type'];
            $file_size = $_FILES['file']['size'];
            $tmp = explode('.', $_FILES['file']['name']);
            $file_ext = strtolower(end($tmp));
            $file = $path . $file_name;
            if ($file_size > 2097152) {
                $errors[] = 'File size exceeds limit: ' . $file_name . ' ' . $file_type;
            }
            if (empty($errors)) {
                move_uploaded_file($file_tmp, $file);
                $query = "INSERT INTO attachment (file_name) VALUES ('$file_name') RETURNING *";
                $result = $this->db->execute($query);
                $num = $result->rowCount();

                // jika ada hasil
                if ($num > 0) {

                    $data_arr = array();

                    while ($row = $result->fetchRow()) {
                        extract($row);

                        // Push to data_arr

                        $data_item = array(
                            'file_name' => $file_name,
                        );

                        array_push($data_arr, $data_item);
                        $msg = $data_arr;
                    }

                } else {
                    $msg = 'Data Kosong';
                }

                return $msg;
            }
            // }
            if ($errors) {
                print_r($errors);
            }

        }

    }
}
