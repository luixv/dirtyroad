<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class ModPhotoStationHelper {
    private static function transformLanguage($lang) {
        switch ($lang) {
            case 'zh-TW':
                return 'cht';
            case 'ja-JP':
                return 'jpn';
            case 'en-GB':
                // fall through
            default:
                return 'enu';
        }
    }

    public static function getData($params) {
        $data = array(
            'diskstation' => $params->get('diskstation'),
            'protocol' => $params->get('protocol'),
            'id' => '',
            'type' => '');
        $db = JFactory::getDbo();
        if (!$db) {
            return $data;
        }
        //$query = $db->getQuery(true)->select($db->quoteName('share'))->from($db->quoteName('#__photostation'))->where($db->quoteName('usr') . ' = ' . $db->quote(JFactory::getUser()->username));
        $select = implode(', ', array(
            $db->quoteName('id'),
            $db->quoteName('share')));
        $query = $db->getQuery(true)->select($select)->from($db->quoteName('#__photostation'));
        if (!$query) {
            return $data;
        }
        $db->setQuery($query);
        $row = $db->loadRow();
        if ($row) {
            $data['id'] = $row[0];
            $data['type'] = explode('_', $row[0])[0];
            $data['share'] = $row[1];
        }

        // Additional data
        $data['target'] = $data['protocol'] . '://' . $data['diskstation'];
        $data['lang'] = self::transformLanguage(JFactory::getLanguage()->getTag());

        return $data;
    }

    public static function setAjax() {
        $db = JFactory::getDbo();
        if (!$db) {
            return;
        }
        $table = $db->quoteName('#__photostation');
        $usr = $db->quoteName('usr');
        $username = $db->quote(JFactory::getUser()->username);
        $where = $usr . ' = ' . $username;
        $query = $db->getQuery(true)->select('*')->from($table)->where($where);
        if (!$query) {
            return;
        }
        $input = JFactory::getApplication()->input;
        $id = $db->quoteName('id');
        $val_id = $db->quote($input->get('id'));
        $share = $db->quoteName('share');
        $val_share = $db->quote($input->get('share'));
        $db->setQuery($query);
        if (!$db->loadRow()) {
            $columns = implode(', ', array($usr, $id, $share));
            $values = implode(', ', array($username, $val_id, $val_share));
            $query = $db->getQuery(true)->insert($table)->columns($columns)->values($values);
        } else {
            $set = implode(', ', array(
                $id . ' = ' . $val_id,
                $share . ' = ' . $val_share));
            $query = $db->getQuery(true)->update($table)->set($set)->where($where);
        }
        if (!$query) {
            return;
        }
        $db->setQuery($query);
        $db->execute();
    }
}
