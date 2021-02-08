<?php /* MIT License

Copyright (c) 2021 Todd Perry

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE. */

function mod_generate_gem($userid, $stxt, $category) {
  $sql = 'SELECT MAX(tokid) as maxid FROM toks';
  $rs = queryf_one($sql);
  $maxid = $rs['maxid'];

  $randtokid = rand(0, $maxid - 1);
  $sql = 'SELECT chestidstr FROM toks WHERE tokid = %d';
  $rs = queryf_one($sql, $randtokid);
  $chestidstr = $rs['chestidstr'];

  $chestidarr = explode(',', $chestidstr);
  $chestcount = count($chestidarr);
  $randindex = rand(0, $chestcount - 1);
  $randchestid = $chestidarr[$randindex];

  $sql = 'SELECT datastr FROM chests WHERE chestid = %d';
  $rs = queryf_one($sql, $randchestid);
  $datastr = $rs['datastr'];

  $wordcount = count(explode(' ', $datastr));
  $charcount = strlen($datastr) - $wordcount;

  $user_data = user_get_ans_data($userid);

  $nowtime = time();
  $sql = 'INSERT INTO gems (userid, chestid, tokid, stepint, datecreated,';
  $sql .= ' wordcount, charcount, lastloaded, ansrows, anscols)';
  $sql .= ' VALUES (%d, %d, %d, %d, %d, %d, %d, %d, %d, %d)';
  queryf($sql, $userid, $randchestid, $randtokid, 0, $nowtime, $wordcount,
         $charcount, $nowtime, $user_data['datarows'], $user_data['datacols']);
  return last_insert_id();
}

function mod_get_gem_book($gem_id) {
  $sql = 'SELECT chestid FROM gems WHERE gemid = %d';
  $rs = queryf_one($sql, $gem_id);
  $sql = 'SELECT bookid FROM chests WHERE chestid = %d';
  $rs2 = queryf_one($sql, $rs['chestid']);
  return $rs2['bookid'];
}

function mod_get_gem_auth($gem_id) {
  $book_id = mod_get_gem_book($gem_id);
  $sql = 'SELECT authorstr FROM books WHERE bookid = %d';
  $rs = queryf_one($sql, $book_id);
  return $rs['authorstr'];
}

function mod_update_gem_auth_and_text($gem_id, $auth_str, $text_str) {
  $sql = 'UPDATE gems SET authguess = %s, bookguess = %s';
  $sql .= ' WHERE gemid = %d';
  queryf($sql, $auth_str, $text_str, $gem_id);
}

function mod_update_gem_step($gemid, $newstep) {
  $sql = 'UPDATE gems SET stepint = %d';
  $sql .= ' WHERE gemid = %d';
  queryf($sql, $newstep, $gemid);
}

function mod_load_gem($gemid) {
  $sql = 'SELECT * FROM gems WHERE gemid = %d';
  $rv = queryf_one($sql, $gemid);

    if ($rv['authguess'] === null) {
    $rv['authstr'] = 'n/a';
  } else {
    $rv['authstr'] = $rv['authguess'];
  }

  if ($rv['bookguess'] == 0) {
    $rv['bookstr'] = 'n/a';
  } else {
    $rv['bookstr'] = mod_get_book_title($rv['bookguess']);
  }

  $sql = 'SELECT tokstr FROM toks WHERE tokid = %d';
  $rs = queryf_one($sql, $rv['tokid']);
  $rv['tokstr'] = $rs['tokstr'];

  $sql = 'SELECT datastr FROM chests WHERE chestid = %d';
  $rs = queryf_one($sql, $rv['chestid']);
  $rv['datastr'] = $rs['datastr'];

  $rv['chester'] = preg_replace('/' . $rv['tokstr'] . '/i',
                                '_______', $rv['datastr']);
  return $rv;
}

function mod_update_gem_lastloaded($gemid) {
  $nowtime = time();
  $sql = 'UPDATE gems SET lastloaded = %d';
  $sql .= ' WHERE gemid = %d';
  queryf($sql, $nowtime, $gemid);
}