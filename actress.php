<?php
require('common/util.php');
require('common/ini.php');
if(isset($_GET['article_id'])){
	$article_id = $_GET['article_id'];
}
$sql = "SELECT * FROM dmm WHERE actress_id = ".$article_id;
$stmt = $dbh->prepare($sql);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);
if($data['actress_name'] <> ''):$name = $data['actress_name'];else:$name = '--';endif;
if($data['actress_ruby'] <> ''):$ruby = $data['actress_ruby'];else:$ruby = '';endif;

$article_api = $videoa_api.'&article=actress&article_id='.$article_id.'&keyword='.$keyword;
$article_xml = simplexml_load_string(file_get_contents($article_api));
$total_count = $article_xml->result->total_count;
$pager = ceil($total_count / 100);
?>

<?php include('common/header.php');?>
<?php //echo '<pre>';print_r($actress_xml);echo '</pre>';?>
<?php //echo '<pre>';print_r($article_xml);echo '</pre>';?>

<header class="header" id="page-header"><h1 class="main-title"><span class="ttl serif"><?=$name;?></span><?=$ruby;?></h1></header>
<section class="wrap-flex" id="actress-detail">
    <div class="actress-detail__box">
        <figure class="thum">
            <?php if($data['actress_img'] == '' || $data['actress_img'] == 'http://pics.dmm.co.jp/mono/actjpgs/printing.jpg'):?>
                <img src="img/nowprinting.gif" alt="<?=$name;?>">
            <?php else:?>
                <img src="<?=$data['actress_img'];?>" alt="<?=$name;?>">
            <?php endif;?>        
        </figure>
    </div>
    <div class="actress-detail__box">
        <table id="tbl-actress">
            <tr><th>生年月日:</th><td><?php if($data['actress_birthday']<>''):
                $birthdays=date_create($data['actress_birthday']);
                $age = floor((date('Ymd') - $birthdays->format('Ymd'))/10000);
                echo date_format($birthdays,'Y年n月j日').'('.$age.'歳)';
                else:echo '--';endif;?></td></tr>
            <tr><th>サイズ:</th><td><?php if($data['actress_height']>0 || $data['actress_bust']>0 || $data['actress_waist']>0 || $data['actress_hip']>0):
                if($data['actress_height']>0):echo 'T'.$data['actress_height'].'cm';endif;
                if($data['actress_bust']>0):echo ' B'.$data['actress_bust'].'cm';endif;
                if($data['actress_cup']<>''):echo '('.$data['actress_cup'].')';endif;
                if($data['actress_waist']>0):echo ' W'.$data['actress_waist'].'cm';endif;
                if($data['actress_hip']>0):echo ' H'.$data['actress_hip'].'cm';endif;
            else:echo '--';endif;?></td></tr>
            <tr><th>血液型:</th><td><?php if($data['actress_blood_type']<>''):echo $data['actress_blood_type'].'型';else: echo '--';endif;?></td></tr>
            <tr><th>趣味･特技:</th><td><?php if($data['actress_hobby']<>''):echo $data['actress_hobby'];else: echo '--';endif;?></td></tr>
            <tr><th>出身地:</th><td><?php if($data['actress_prefectures']<>''):echo $data['actress_prefectures'];else: echo '--';endif;?></td></tr>
        </table>
    </div>
</section>

<?php if($total_count <> 0):?>
<section>
	<header class="header"><h1 class="main-title"><span class="ttl serif"><?=$name;?>の出演作品</span><?php include('common/count.php');?>件</h1></header>
    <?php if($total_count > 1):include('common/list-sort.php');endif;?>
   
    <ul class="wrap-flex product-list">
	<?php foreach($article_xml->result->items->item as $val):
		include('common/product-list.php');
	endforeach;?>
    </ul>
</section>
<?php endif;?>
<?php include('common/pager.php');?>

<?php include('common/footer.php');?>