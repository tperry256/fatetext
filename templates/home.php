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

if (web_logged_in()) {

  echo gen_search_form();

  //$sub_header_str .= gen_i('Coins');
  $sub_header_str = gen_link(gen_url('coin'), 'Coins', 'header');
  //$temp_str = gen_i('Store');
  $temp_str = gen_link(gen_url('store'), 'Store', 'header');
  $sub_header_str .= ' | ' . $temp_str . ' | ';
  $sub_header_str .= gen_i('Space');
  //$sub_header_str .= gen_link(gen_url('home'), 'Space', 'header');
  echo gen_p(gen_h(2, $sub_header_str));

  echo gen_h(3, 'Advertising Dashboard');

  $gemco = gen_img('images/tree.png', 'Palm Trees from Stanford', 32);
  $c_str = 'We live here' . PADDING_STR . $gemco;
  $con_str = gen_p($c_str, 'page_heading');

  $link_str = 'Back to Gems';
  $con_str .= gen_p(gen_link(gen_url('search'), $link_str));
  echo gen_div($con_str, 'content');

} else { //not logged in

  $hall_url = gen_url('hall');

  echo gen_login_form();
  $link_str = 'Hall of Fame';
  $artnum = count(mod_get_hall_art());
  $hall_str = '[' . $artnum . ']' . PADDING_STR;
  $hall_str .= gen_link($hall_url, $link_str); 
  echo gen_p($hall_str);

}
?>
