<?php
require('common/util.php');
require('common/ini.php');
if(isset($_GET)){/*print_r($_GET);*/}
if(isset($_GET['gtelte_cup'])){$gtelte_cup = $_GET['gtelte_cup'];}else{$gtelte_cup = '';}
if(isset($_GET['gte_height'])){$gte_height = $_GET['gte_height'];}else{$gte_height = '';}
if(isset($_GET['gte_bust'])){$gte_bust = $_GET['gte_bust'];}else{$gte_bust = '';}
if(isset($_GET['gte_waist'])){$gte_waist = $_GET['gte_waist'];}else{$gte_waist = '';}
if(isset($_GET['gte_hip'])){$gte_hip = $_GET['gte_hip'];}else{$gte_hip = '';}
//if(isset($_GET['gte_birshday'])){$gte_birshday = $_GET['gte_birshday'];}else{$gte_birshday = '';}
if(isset($_GET['lte_height'])){$lte_height = $_GET['lte_height'];}else{$lte_height = '';}
if(isset($_GET['lte_bust'])){$lte_bust = $_GET['lte_bust'];}else{$lte_bust = '';}
//if(isset($_GET['lte_cup'])){$lte_bust = $_GET['lte_cup'];}else{$lte_bust = '';}
if(isset($_GET['lte_waist'])){$lte_waist = $_GET['lte_waist'];}else{$lte_waist = '';}
if(isset($_GET['lte_hip'])){$lte_hip = $_GET['lte_hip'];}else{$lte_hip = '';}
//if(isset($_GET['lte_birshday'])){$lte_birshday = $_GET['lte_birshday'];}else{$lte_birshday = '';}
if(isset($_GET['actress_sort'])){$actress_sort = $_GET['actress_sort'];}else{$actress_sort = '';}

$actress_api .= '&hits=100&sort='.$actress_sort.'&cup='.$gtelte_cup.'&offset='.$offset.'&gte_height='.$gte_height.'&lte_height='.$lte_height.'&gte_bust='.$gte_bust.'&lte_bust='.$lte_bust.'&gte_waist='.$gte_waist.'&lte_waist='.$lte_waist.'&gte_hip='.$gte_hip.'&lte_hip='.$lte_hip;
if($initial<>''):
	$actress_api .= '&initial='.$initial;
else:	
	$actress_api .= '&keyword='.$keyword;
endif;
$actress_xml = simplexml_load_string(file_get_contents($actress_api));
$total_count = $actress_xml->result->total_count;
//print_r($actress_xml);
/*
$sql = "SELECT * FROM dmm WHERE (actress_height BETWEEN ".$gte_height." AND ".$lte_height.") AND (actress_bust BETWEEN ".$gte_bust." AND ".$lte_bust.") AND (actress_waist BETWEEN ".$gte_waist." AND ".$lte_waist.") AND (actress_hip BETWEEN ".$gte_hip." AND ".$lte_hip.")";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$data = $stmt->fetchall(PDO::FETCH_ASSOC);
$total_count = count($data);
*/
//print_r($data);
$pager = ceil($total_count / 100);
?>
<!DOCTYPE html>
<html>
<head>
<?php include('common/head.php');?>
<title>AV女優検索<?php if($pager > 1):echo '｜'.$page.'ページ';endif;?></title>

<?php include('common/header.php');?>
<header class="header" id="page-header">
	<h1 class="main-title">
    	<span class="ttl"><?php if($initial<>''):?>｢<?=$initial;?>｣で始まるAV女優<?php else:?>AV女優検索<?php endif;?></span>
		<?php include('common/count.php');?>人
    </h1>
</header>
<section>
	<?php //include('common/list-sort.php');?>
    <form id="actress_sort_form" action="actress_search.php" method="get">
    	<select id="actress_sort_select" name="actress_sort">
        	<option value="name"<?php if($actress_sort == 'name'):echo ' selected';endif?>>名前順</option>
            <option value="-name"<?php if($actress_sort == '-name'):echo ' selected';endif?>>名前逆順</option>
            <option value="height"<?php if($actress_sort == 'height'):echo ' selected';endif?>>身長が低い順</option>
            <option value="-height"<?php if($actress_sort == '-height'):echo ' selected';endif?>>身長が高い順</option>
            <option value="bust"<?php if($actress_sort == 'bust'):echo ' selected';endif?>>バストが小さい順</option>
            <option value="-bust"<?php if($actress_sort == '-bust'):echo ' selected';endif?>>バストが大きい順</option>
            <option value="waist"<?php if($actress_sort == 'waist'):echo ' selected';endif?>>ウェストが細い順</option>
            <option value="-waist"<?php if($actress_sort == '-waist'):echo ' selected';endif?>>ウェストが太い順</option>
            <option value="hip"<?php if($actress_sort == 'hip'):echo ' selected';endif?>>ヒップが小さい順</option>
            <option value="-hip"<?php if($actress_sort == '-hip'):echo ' selected';endif?>>ヒップが大きい順</option>
            <option value="-birthday"<?php if($actress_sort == '-birthday'):echo ' selected';endif?>>若い順</option>
            <option value="birthday"<?php if($actress_sort == 'birthday'):echo ' selected';endif?>>熟女順</option>
        </select>
        <input type="hidden" name="gte_height" value="<?=$gte_height;?>">
        <input type="hidden" name="lte_height" value="<?=$lte_height;?>">
        <input type="hidden" name="gte_bust" value="<?=$gte_bust;?>">
        <input type="hidden" name="lte_bust" value="<?=$lte_bust;?>">
        <input type="hidden" name="gte_waist" value="<?=$gte_waist;?>">
        <input type="hidden" name="lte_waist" value="<?=$lte_waist;?>">
        <input type="hidden" name="gte_hip" value="<?=$gte_hip;?>">
        <input type="hidden" name="keyword" value="<?=$keyword;?>">
        <input type="hidden" name="initial" value="<?=$initial;?>">
        <!--<input type="submit" value="検索" />-->
    </form>
    <ul class="wrap-flex actress-list">
    <?php
		//foreach($data as $data):include('common/actress-list.php');endforeach;
		if($actress_xml->result->actress->item):
			foreach($actress_xml->result->actress->item as $val):
				if(isset($val)):
					$sql = "SELECT * FROM dmm WHERE actress_id = ".$val->id;
					$stmt = $dbh->prepare($sql);
					$stmt->execute();
					$data = $stmt->fetch(PDO::FETCH_ASSOC);
					//if($data['actress_cup'] == $gtelte_cup):
						include('common/actress-list.php');
					//endif;
				endif;
			endforeach;
		endif;
	?>
    </ul>
</section>
<?php include('common/pager.php');?>
</main><!-- main -->
<?php include('common/aside.php');?>
<?php include('common/footer.php');?>