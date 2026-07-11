<?php

namespace App\Models;

use CodeIgniter\Model;

class BaseModel extends Model
{
    public function allData(string $table, string $orderBy = 'id', string $orderType = 'DESC'): array
    {
        return $this->db->table($table)
            ->orderBy($orderBy, $orderType)
            ->get()
            ->getResultArray();
    }

    public function dataWhere(string $table, string $field, $value): array
    {
        return $this->db->table($table)
            ->where($field, $value)
            ->get()
            ->getResultArray();
    }

    public function dataWhereArray(string $table, array $where): array
    {
        return $this->db->table($table)
            ->where($where)
            ->get()
            ->getResultArray();
    }

    public function dataInsert(string $table, array $data): bool
    {
        return $this->db->table($table)->insert($data);
    }

    public function dataUpdate(string $table, array $data, $id, string $field = 'id'): bool
    {
        return $this->db->table($table)
            ->where($field, $id)
            ->update($data);
    }

    public function dataDelete(string $table, $id, string $field = 'id'): bool
    {
        return $this->db->table($table)
            ->where($field, $id)
            ->delete();
    }

    public function countData(string $table, array $where = []): int
    {
        $builder = $this->db->table($table);

        if (! empty($where)) {
            $builder->where($where);
        }

        return $builder->countAllResults();
    }

    public function dataLike(
        string $table,
        string $field,
        string $keyword,
        array $where = [],
        string $orderBy = 'id',
        string $orderType = 'DESC',
        int $limit = 20
    ): array {
        $builder = $this->db->table($table);

        if (! empty($where)) {
            $builder->where($where);
        }

        return $builder
            ->like($field, $keyword)
            ->orderBy($orderBy, $orderType)
            ->limit($limit)
            ->get()
            ->getResultArray();
    }
}
