<?php


class Database // chuyên đọc, chỉnh sưa, xóa, thêm file json
{
    public $file_path;

    public function __construct($file_path)
    {
        $this->file_path = $file_path;
        if (!file_exists($this->file_path)) {
            file_put_contents($this->file_path, json_encode([]));
        } else {
            if (file_get_contents($this->file_path) == '') {
                file_put_contents($this->file_path, json_encode([]));
            }
        }
    }

    // lấy tất cả
    public function getAll()
    {
        try {
            return json_decode(file_get_contents($this->file_path), true);
        } catch (\Throwable $th) {
            return [];
        }
    }
    public function get($id)
    {
        $data_json = file_get_contents($this->file_path);
        $data = json_decode($data_json, true);
        foreach ($data as $key => $value) {
            if ($value['id'] == $id) {
                return $value;
            }
        }
        return null;
    }
    public function insert($data_insert)
    { // hàm thêm
        $data_json = file_get_contents($this->file_path); // đọc file json cũ
        $data = json_decode($data_json, true); // chuyển thành mảng
        array_unshift($data, $data_insert); // thêm phần tử mới

        file_put_contents($this->file_path, json_encode($data)); // lưu lại file json mới
    }
    public function delete($id)
    {
        $data_json = file_get_contents($this->file_path);
        $data = json_decode($data_json, true);
        foreach ($data as $key => $value) {
            if ($value['id'] == $id) {
                unset($data[$key]);
            }
        }
        file_put_contents($this->file_path, json_encode($data));
    }

    public function update($id, $data_update)
    {
        $data_json = file_get_contents($this->file_path);
        $data = json_decode($data_json, true);
        foreach ($data as $key => $value) {
            if ($value['id'] == $id) {
                $data[$key] = $data_update;
            }
        }

        file_put_contents($this->file_path, json_encode($data));
    }

}
