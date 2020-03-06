<?php

class RequestAccount
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
             $tablename";

        $result = $this->db->execute($query);

        $num = $result->rowCount();

        if ($num > 0) {

            $data_arr = array();

            while ($row = $result->fetchRow()) {
                extract($row);

                $data_item = array(
                    'id' => $id,
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'username' => $username,
                    'password' => $password,
                    'role' => $role_id,
                    'unit' => $unit_id,
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
        $handle = $this->db->prepare($query);
        $result = $this->db->execute($handle);
        $row = $result->fetchRow();
        if (is_bool($row)) {
            $msg = "Data Kosong";
            return $msg;
        } else {
            extract($row);
            $data_item = array(
                'id' => $id,
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'username' => $username,
                'password' => $password,
                'role' => $role_id,
                'unit' => $unit_id,
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
        $variable = array('nik');
        foreach ($variable as $item) {
            if (!isset($request[0]->{$item})) {
                return "422";
            }

            $$item = $request[0]->{$item};
        }

        // $name = $request[0]->name;
        // $email = $request[0]->email;
        // $phone = $request[0]->phone;
        // $username = $request[0]->username;
        // $password = $request[0]->password;
        // $role_id = $request[0]->role_id;
        // $unit_id = $request[0]->unit_id;
        // $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $get_employee = "SELECT DISTINCT * FROM employee WHERE n_nik = '$nik'";

        $result = $this->db->execute($get_employee);
        $row = $result->fetchRow();
        if (is_bool($row)) {
            $msg = "Data Kosong";
        } else {
            extract($row);

            $data_item = array(
                'c_company_code' => $c_company_code,
                'v_company_code' => $v_company_code,
                'c_kode_divisi' => $c_kode_divisi,
                'v_short_divisi' => $v_short_divisi,
                'c_kode_unit' => $c_kode_unit,
                'v_short_unit' => $v_short_unit,
                'v_long_unit' => $v_long_unit,
                'objidposisi' => $objidposisi,
                'c_kode_posisi' => $c_kode_posisi,
                'v_short_posisi' => $v_short_posisi,
                'v_long_posisi' => $v_long_posisi,
                'c_flag_chief' => $c_flag_chief,
                'n_nik' => $n_nik,
                'v_nama_karyawan' => $v_nama_karyawan,
                'v_jenis_kelamin' => $v_jenis_kelamin,
                'v_personnel_subarea' => $v_personnel_subarea,
            );

            $get_unit = $get_unit = "SELECT * FROM unit WHERE LOWER(code) = LOWER('" . $data_item['c_kode_unit'] . "')";
            $result = $this->db->execute($get_unit);
            $unit = $result->fetchRow();

            $query = "INSERT INTO $tablename (name, username, unit_id)";
            $query .= "VALUES ('" . $data_item['v_nama_karyawan'] . "','$nik', '" . $unit['id'] . "') RETURNING *";
            // die($query);
            $result = $this->db->execute($query);
            if (empty($result)) {
                return "422";
            } else {
                $num = $result->rowCount();

                if ($num > 0) {

                    $data_arr = array();

                    while ($row = $result->fetchRow()) {
                        extract($row);

                        $data_item = array(
                            'id' => $id,
                            'name' => $name,
                            'username' => $username,
                            'unit_id' => $unit_id,
                        );

                        array_push($data_arr, $data_item);
                        $msg = $data_arr;
                    }

                }
            }
            return $msg;
        }

    }

    public function update($id, $tablename)
    {
        // get data input from frontend
        $data = file_get_contents("php://input");
        //
        $request = json_decode($data);
        $name = $request[0]->name;
        $email = $request[0]->email;
        $phone = $request[0]->phone;
        $username = $request[0]->username;
        $password = $request[0]->password;
        $role_id = $request[0]->role_id;
        $unit_id = $request[0]->unit_id;
        // $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $query = "UPDATE $tablename SET name = '$name', email = '$email', phone = '$phone', username = ' $username', password = '$password', role_id = '$role_id', unit_id = '$unit_id' WHERE id = '$id'";
        // die($query);
        $result = $this->db->execute($query);
        if (empty($result)) {
            return "422";
        } else {
            $num = $result->rowCount();

            if ($num > 0) {

                $data_arr = array();

                while ($row = $result->fetchRow()) {
                    extract($row);

                    $data_item = array(
                        'id' => $id,
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone,
                        'username' => $username,
                        'password' => $password,
                        'role' => $role_id,
                        'unit' => $unit_id,
                    );

                    array_push($data_arr, $data_item);
                    $msg = $data_arr;
                }

            }
        }
        return $msg;
    }

    public function delete($id, $tablename)
    {
        $query = "DELETE FROM $tablename WHERE id = $id";
        // die($query);
        $this->db->execute($query);
        $res = $this->db->affected_rows();

        if ($res == true) {

            return $msg = array("message" => 'Data Berhasil Dihapus', "code" => 200);
        } else {
            return $msg = "Data Kosong";
        }
        // }

    }
}
