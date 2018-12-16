<?php

/*
 * The MIT License
 *
 * Copyright 2018 mariusz.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */


namespace App\Controllers;

use PDO;

/**
 * Description of ApiController
 *
 * @author mariusz
 */
class ApiController extends Controller {

    function __construct($container) {
        parent::__construct($container);
        $this->CreateRowForDay();
    }

    private function CreateRowForDay() {
        // Create new row
        $sql = "SELECT `_rowid_` FROM `votes` WHERE DATE = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([date('Y-m-d')]);
        if (!$stmt->fetch()) {
            $dt = date('Y-m-d');
            if (!$this->db->query("INSERT INTO `votes`(`ID`, `DATE`) VALUES (NULL, '$dt');")) {
                return json_encode($this->db->lastErrorMsg());
            }
        }
    }

    public function Vote(\Slim\Http\Request $request, \Slim\Http\Response $response) {
        $sayyes = $request->getParsedBodyParam('sayyes');
        $sayno = $request->getParsedBodyParam('sayno');

        $result = Null;
        if ($sayyes == 1) {
            $result = $this->VoteUp();
        } else if ($sayno == 1) {
            $result = $this->VoteDown();
        } else {
            $result = array('status' => 500, 'error' => array('text' => 'Błędny typ głosu!'));
        }
        return $response->withJson($result, $result['status']);
    }

    private function VoteUp() {
        $sql = "UPDATE `votes` SET `YES` = `YES` + 1 WHERE DATE = ?";
        return $this->QueryVote($sql);
    }

    private function VoteDown() {
        $sql = "UPDATE `votes` SET `NO` = `NO` + 1 WHERE DATE = ?";
        return $this->QueryVote($sql);
    }

    private function QueryVote(string $sql) {
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([date('Y-m-d')]);
        if ($result) {
            return array('status' => 200, 'message' => array('text' => 'Głos został oddany!'));
        } else {
            return array('status' => 500, 'error' => array('text' => $this->db->lastErrorMsg()));
        }
    }

    public function GetVotes(\Slim\Http\Request $request, \Slim\Http\Response $response) {
        try {
            $addns = '';
            $type = $request->getAttribute('type');

            if (isset($type) && ($type == "today")) {
                $addns = ' WHERE DATE = "' . date('Y-m-d') . '"';
            }

            $sth = $this->db->query(
                    "
            select
               DATE as `date`,
               `YES`, `NO`, (`YES` + `NO`) as TOTAL
            from
               votes
            " . $addns . "
            GROUP BY DATE");
            $votes = $sth->fetchall(PDO::FETCH_ASSOC);
            return $response->withJson($votes, 201);
        } catch (PDOException $e) {
            $err = array('status' => 500, 'error' => array('text' => $e->getMessage()));
            return $response->withJson($err, 500);
        }
    }

    public function GetGuruLevels(\Slim\Http\Request $request, \Slim\Http\Response $response) {
        try {
            $addns = ' WHERE (`YES` + `NO`) > 0 ';
            $type = $request->getAttribute('type');
            if (isset($type) && ($type == "today")) {
                $addns .= ' AND DATE = "' . date('Y-m-d') . '"';
            }
            $sth = $this->db->query(
                    "
            select
               DATE as `date`,
               CAST( (CAST(`YES` as REAL) / CAST((`YES` + `NO`) as REAL) ) as REAL) as `value`,
               (`YES` + `NO`) as 'total'
            from
               votes
            " . $addns . "
            GROUP BY DATE");
            $votes = $sth->fetchall(PDO::FETCH_ASSOC);
            return $response->withJson($votes, 201);
        } catch (PDOException $e) {
            $err = array('status' => 500, 'error' => array('text' => $e->getMessage()));
            return $response->withJson($err, 500);
        }
    }

}
