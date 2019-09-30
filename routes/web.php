<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $crawler = Goutte::request('GET', 'https://baogialai.com.vn/channel/8301/');
    $news = $crawler->filter('#cate-content > li')->each(function($node) {
        $list = $node->filter('.title-2');
        $img = $node->filter('.avatar > img');
        $des = $node->filter('.lead');
        $chil = Goutte::request('GET', 'https://baogialai.com.vn' . $list->attr('href'));
        $chil_new = $chil->filter('#content > div')->each(function ($c_node){
            $node = $c_node->filter('div')->eq(0)->html();
            return $node;
        });

        unset($chil_new[0]);
        unset($chil_new[1]);
        unset($chil_new[2]);
        unset($chil_new[3]);

        return [
            'title' => $list->text(),
            'link' => $list->attr('href'),
            'img' => $img->attr('src'),
            'des' => str_replace('(GLO)- ','', $des->text()),
            'content' => $chil_new,
        ];
    });

    print_r($news);

    return view('welcome');





});
