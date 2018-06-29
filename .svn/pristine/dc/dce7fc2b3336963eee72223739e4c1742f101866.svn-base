<?php
/**
 * Created by PhpStorm.
 * User: varro
 * Date: 2018/5/5
 * Time: 15:42
 */

namespace Common\Model;

use Think\Model;
use Ku;

class NoticeModel extends Model
{
    protected $tableName = 'notice';
    protected $pk = 'notice_id';

    public function getNoticeList(array $data = [])
    {
        $sql = "SELECT * FROM notice";

        if (!empty($data['page']) && !empty($data['limit'])) {
            $sql .= Ku\Utils::setLimit($data['page']);
        }

        return $this->query($sql);
    }

    public function addNotice($params)
    {
        return !$this->create($params) ?: $this->add($params);
    }

    public function updateNotice($params, string $where)
    {
        return $this->where($where)->save($params);
    }
}
