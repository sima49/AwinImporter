<?php

class DB extends PDO {

    public function __construct($cat) {
        $config = include($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
        //parent::__construct();
        try {
            $this->pdo = new PDO("mysql:host=" . $config->host . ";dbname=" . $config->dbname . "", $config->user, $config->pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// always disable emulated prepared statement when using the MySQL driver
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->table = str_replace(' ', '_', $cat);
            $query = "CREATE TABLE IF NOT EXISTS $this->table (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        product_name VARCHAR(255),
        aw_deep_link VARCHAR(255),
        aw_product_id VARCHAR(255),
        merchant_product_id VARCHAR(255),
        merchant_image_url VARCHAR(255),
        description TEXT(3000),
        merchant_category VARCHAR(255),
        search_price DECIMAL(12,2),
        brand_name VARCHAR(100),
        promotional_text TEXT(3000),
        aw_image_url VARCHAR(255),
        category_name VARCHAR(255)
    )COLLATE='utf8_unicode_ci'";
            $this->pdo->exec($query);
            //echo 'table created';
            echo 'ITEMS IMPORTED SUCCESSFULLY';
        } catch (PDOException $e) {
// We got an exception == table not found
            {
                echo $query . "<br>" . $e->getMessage();
            }
        }
// Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
// return $result !== FALSE;
    }

    function placeholders($text, $count = 0, $separator = ",") {
        $result = array();
        if ($count > 0) {
            for ($x = 0; $x < $count; $x++) {
                $result[] = $text;
            }
        }

        return implode($separator, $result);
    }

    function pdo_insert($table, $arry) {
        if (!is_array($arry) || !count($arry))
            return false;
        array_map(function ($arr) {
            $arr;

            // your pdo connection
            $bind = ':' . implode(',:', array_keys($arr));
            $sql = 'insert into ' . $this->table . '(' . implode(',', array_keys($arr)) . ') ' .
                    'values (' . $bind . ')';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(array_combine(explode(',', $bind), array_values($arr)));

            if ($stmt->rowCount() > 0) {
                return true;
            }

            return false;
        }, $arry);
    }

}
