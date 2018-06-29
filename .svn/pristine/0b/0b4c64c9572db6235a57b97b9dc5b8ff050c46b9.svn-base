<?php
/**
 * Created by PhpStorm.
 * User: varro
 * Date: 2018/4/17
 * Time: 13:48
 */

namespace Common\Model;

use Think\Model;
use Ku;

class UserModel extends Model
{
    protected $tableName = 'user';
    protected $pk = 'user_id';

    public function getUser($userId)
    {
        return $this->where("user_id = '$userId'")->find();
    }

    public function getUserAccount($account)
    {
        return $this->where("account = '$account'")->find();
    }

    public function getStudent($userId)
    {
        return $this->join('student on student.user_id = user.user_id')->where("user.user_id = $userId")->find();
    }

    public function getTeacher($userId)
    {
        return $this->join('teacher on teacher.user_id = user.user_id')->where("user.user_id = $userId")->find();
    }

    public function getManager($userId)
    {
        return $this->join('manager on manager.user_id = user.user_id')->where("user.user_id = $userId")->find();
    }

    public function getAdmin($userId)
    {
        return $this->join('admin on admin.user_id = user.user_id')->where("user.user_id = $userId")->find();
    }

    public function addUser(array $params)
    {
        return $this->add($params);
    }

    public function getUserInfo(array $data)
    {
        $sql = "select a.*, b.role_name from user a join role b on b.role_id = a.role_id";

        $whereArray = [];
        if (!empty($data['user_id'])) {
            $whereArray[] = "user_id = '{$data['user_id']}'";
        }

        if (!empty($data['account'])) {
            $whereArray[] = "account = '{$data['account']}'";
        }

        $where = implode($whereArray, ' and ');
        $sql .= $where ? " where $where " : "";

        $user = current($this->query($sql));
        if ($user['role_id'] == 6) {
            return ($this->getStudent($user['user_id']) + ['role_name' => $user['role_name']]);
        }

        if ($user['role_id'] == 5) {
            return $this->getTeacher($user['user_id']) + ['role_name' => $user['role_name']];
        }

        if ($user['role_id'] == 2 ) {
            return $this->getManager($user['user_id']) + ['role_name' => $user['role_name']];
        }

        if ($user['role_id'] == 1 ) {
            return $this->getAdmin($user['user_id']) + ['role_name' => $user['role_name']];
        }

        return [];
    }

    public function getUserList(array $data = [])
    {
        $sql =
            "SELECT * from (
                ( 
                SELECT
                    a.account,
                    a.user_id,
                    a.role_id,
                    a.create_time,
                    a.status as user_status,
                    b.role_name,
                    c.phone,
                    c.name,
                IF
                    ( c.STATUS = 1, '在读', '毕业' ) AS status 
                FROM
                    USER a
                    JOIN role b ON a.role_id = b.role_id
                    JOIN student c ON c.user_id = a.user_id
                )
                    UNION 
                (
                SELECT
                    a.account,
                    a.user_id,
                    a.role_id,
                    a.create_time,
                    a.status as user_status,
                    b.role_name,
                    c.phone,
                    c.name,
                IF
                    ( c.STATUS = 1, '在任', '离职' ) AS status 
                FROM
                    USER a
                    JOIN role b ON a.role_id = b.role_id
                    JOIN teacher c ON c.user_id = a.user_id
                ) 
                    UNION
                (
                SELECT
                    a.account,
                    a.user_id, 
                    a.role_id,
                    a.create_time,
                    a.status as user_status,
                    b.role_name,
                    c.phone,
                    c.name,
                IF
                    ( c.STATUS = 1, '在任', '离职' ) AS status 
                FROM
                    USER a
                    JOIN role b ON a.role_id = b.role_id
                    JOIN manager c ON c.user_id = a.user_id
                )
                    UNION
                (
                SELECT
                    a.account,
                    a.user_id, 
                    a.role_id,
                    a.create_time,
                    a.status as user_status,
                    b.role_name,
                    c.phone,
                    c.name,
                IF
                    ( c.STATUS = 1, '在任', '离职' ) AS status 
                FROM
                    USER a
                    JOIN role b ON a.role_id = b.role_id
                    JOIN admin c ON c.user_id = a.user_id
                ))tmp";

        $whereArray = [];

        if (isset($data['user_status'])) {
            $whereArray[] = "user_status = {$data['user_status']}";
        } else {
            $whereArray[] = 'user_status = 1';
        }

        if (!empty($data['role_id']) && $data['role_id'] != '全部') {
            $whereArray[] = "role_id = {$data['role_id']}";
        }

        if (isset($data['phone'])) {
            $whereArray[] = "phone = {$data['phone']}";
        }

        if (!empty($data['name'])) {
            $whereArray[] = "name like '%{$data['name']}%'";
        }

        $where = implode(' and ', $whereArray);
        $sql .= $where ? " where {$where}" : "";

        if (!empty($data['page']) && !empty($data['limit'])) {
            $sql .= Ku\Utils::setLimit($data['page']);
        }

        return $this->query($sql);
    }

    public function getUserByTeacher(array $data)
    {
        $sql = "
            SELECT
                a.teacher_id,
                a.user_id,
                a.`name`,
                a.phone,
                a.header_id,
                a.`status`,
                b.account,
                b.role_id,
                b.create_time,
                b.update_time,
                b.reg_time,
                b.`status` AS teacher_status 
            FROM
                `teacher` a
            JOIN `user` b ON a.user_id = b.user_id";

        $whereArray = [];

        if (!empty($data['teacher_id'])) {
            $whereArray[] = "a.teacher_id = {$data['teacher_id']}";
        }

        if (!empty($data['user_id'])) {
            $whereArray[] = "a.user_id = {$data['user_id']}";
        }

        if (!empty($data['key'])) {
            $whereArray[] = "name like '%{$data['key']}%'";
        }

        if (!empty($data['phone'])) {
            $whereArray[] = "a.phone = {$data['phone']}";
        }

        $where = implode(' and ', $whereArray);
        $sql .= $where ? " where {$where}" : "";

        return $this->query($sql);
    }

    public function updateUserInfo($params, string $where)
    {
        return $this->where($where)->save($params);
    }
}
