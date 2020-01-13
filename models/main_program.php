<?php

class MainProgram
{
    public $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function get($tablename)
    {
        $query = "SELECT
           *
          FROM
             $tablename
          ORDER BY
            id ASC";

        // die($query);
        $result = $this->db->execute($query);

        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    'title' => $title,
                    'code' => $code,
                    'unit_id' => $unit_id,
                );

                array_push($data_arr, $data_item);
                $msg = $data_arr;
            }

        } else {
            $msg = 'Data Kosong';
        }

        return $msg;
    }

    public function findById($id, $tablename)
    {
        $query = "SELECT * FROM $tablename WHERE id = '$id'";
        $result = $this->db->execute($query);
        $row = $result->fetchRow();
        if (is_bool($row)) {
            $msg = array("message" => 'Data Tidak Ditemukan', "code" => 400);
            return $msg;
        } else {
            extract($row);

            $data_item = array(
                'id' => $id,
                'title' => $title,
                'code' => $code,
                'unit_id' => $unit_id,
            );
            return $data_item;
        }
    }

    public function insert($tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        $title = $request[0]->title;
        $code = $request[0]->code;
        $unit_id = $request[0]->unit_id;
        $query = "INSERT INTO $tablename (title, code, unit_id)";
        $query .= "VALUES ('$title', '$code','$unit_id')";
        // die($query);
        $result = $this->db->execute($query);
        $num = $result->rowCount();

        $res = $this->db->affected_rows();

        if ($res == true) {
            return $msg = array("message" => 'Data Berhasil Ditambah', "code" => 200);
        } else {
            return $msg = array("message" => 'Data tidak ditemukan', "code" => 400);
        }

    }

    public function update($id, $tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        $title = $request[0]->title;
        $code = $request[0]->code;
        $unit_id = $request[0]->unit_id;
        $query = "UPDATE $tablename SET title = '$title', unit_id = '$unit_id', code = '$code' WHERE id = '$id'";
        // die($query);
        $result = $this->db->execute($query);

        $res = $this->db->affected_rows();

        if ($res == true) {
            return $msg = array("message" => 'Data berhasil diperbaharui', "code" => 200);
        } else {
            return $msg = array("message" => 'Data tidak ditemukan', "code" => 400);
        }
    }

    public function delete($id, $tablename)
    {
        $query = "DELETE FROM $tablename WHERE id = '$id'";
        // die($query);
        $result = $this->db->execute($query);
        // return $result;
        $res = $this->db->affected_rows();

        if ($res == true) {
            return $msg = array("message" => 'Data Berhasil Dihapus', "code" => 200);
        } else {
            return $msg = array("message" => 'Data tidak ditemukan', "code" => 400);
        }
    }
}
