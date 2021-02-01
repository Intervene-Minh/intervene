<?php
    $directory = dirname(__FILE__);
    if(isset($_POST['customdirectory']))
    {
        $directory .= $_POST['customdirectory'];
    }
    $fk = new finkey();
    if(isset($_POST['submit']) && isset($_POST['searchfor']) && isset($_POST['filetype'])){
        $fk->searchfor = $_POST['searchfor'];
        $fk->filetype = $_POST['filetype'];
        if(isset($_POST['regex']) && $_POST['regex']){
            $fk->regex = "m";
        } else{
            $fk->regex = "im";
        }
        $fk->list_files($directory);
        $results = $fk->result;   
    }
class finkey{
    public $result = array();
    public $filetype = array();
    public $searchfor = '';
    public $regex = "im";
    function list_files($directory)
    {
       // header('Content-Type: text/plain');
        if ($directory != '.')
        {
            $directory = rtrim($directory, '/') . '/';
        }
        $lists = scandir($directory);
        foreach($lists as $list){
            if ($list != '.' && $list != '..' && $list != "find.php"){
                if (!is_dir($directory.'/'.$list)){
                    if(in_array($this->getExtension($list), $this->filetype)){
                        $this->findkey($this->searchfor, $directory.$list);  
                    }
                } else {
                    $this->list_files($directory.$list);
                }
            }
        }
    }
    function findkey($searchfor, $file){
        $contents = file_get_contents($file);
        $pattern = preg_quote($searchfor, '/');
        $pattern = "/^.*$pattern.*\$/".$this->regex;
        if(preg_match_all($pattern, $contents, $matches)){
            $temp = array('dir' => $file, 'lines' => $matches[0]);
            $this->result[] =  $temp;
        }   
    }
    function getExtension($str) {  //lấy phần mở rộng của file ảnh để kiểm tra có phải là ảnh ko
        $i = strrpos($str,"."); 
        if (!$i) { return ""; }  
        $ext = substr($str,$i+1); 
        return strtolower($ext); 
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html" />
	<meta name="author" content="Hieu_DT" />
    <style>
        .heading {
          color: blue;
          cursor: pointer;
          font-size: 20px;
          font-weight: bold;
        }
    </style>
    <script type="text/javascript">
        function toggle_line(index){
            var line =  document.getElementById('line_'+index);
            if(line.style.display == '' || line.style.display == 'block'){
                line.style.display = 'none';   
            } else {
                line.style.display = 'block';
            }
        }
    </script>
	<title>Find Content Pro 1.1</title>
</head>

<body>
    <form method="POST" action="" style="text-align: center;">
        <div class="option" id="filetype">
            <?php if(isset($directory)){  ?>
                <h2><?php echo $directory; ?></h2>
            <?php } ?>
            <label>Từ cần tìm: </label><input type="text" value="<?php echo isset($_POST['searchfor']) ? $_POST['searchfor'] : ''; ?>" name="searchfor" /> <br />
            <label>File type: </label>
            <span>Php </span><input type="checkbox" value="php" name="filetype[]" <?php if(isset($_POST['filetype']) && !in_array('php', $_POST['filetype'])){ ?><?php } else{ ?>checked="checked"<?php } ?> />
            <span>Css </span><input type="checkbox" value="css" name="filetype[]" <?php if(isset($_POST['filetype']) && !in_array('css', $_POST['filetype'])){ ?><?php } else{ ?>checked="checked"<?php } ?> />
            <span>Tpl </span><input type="checkbox" value="tpl" name="filetype[]" <?php if(isset($_POST['filetype']) && !in_array('tpl', $_POST['filetype'])){ ?><?php } else{ ?>checked="checked"<?php } ?> /> 
            <span>Js </span><input type="checkbox" value="js" name="filetype[]" <?php if(isset($_POST['filetype']) && !in_array('js', $_POST['filetype'])){ ?><?php } else{ ?>checked="checked"<?php } ?> /> 
            <span>Html </span><input type="checkbox" value="html" name="filetype[]" <?php if(isset($_POST['filetype']) && !in_array('html', $_POST['filetype'])){ ?><?php } else{ ?>checked="checked"<?php } ?> />
            <span>Txt </span><input type="checkbox" value="txt" name="filetype[]" <?php if(isset($_POST['filetype']) && !in_array('txt', $_POST['filetype'])){ ?><?php } else{ ?>checked="checked"<?php } ?> />
            <span>Xml </span><input type="checkbox" value="xml" name="filetype[]" <?php if(isset($_POST['filetype']) && !in_array('xml', $_POST['filetype'])){ ?><?php } else{ ?>checked="checked"<?php } ?> />
            <span>Phtml </span><input type="checkbox" value="phtml" name="filetype[]" <?php if(isset($_POST['filetype']) && !in_array('phtml', $_POST['filetype'])){ ?><?php } else{ ?>checked="checked"<?php } ?> />  <br />
            
        </div>
        <div class="option" id="regex">
            <label>Customer Derectory: </label>
            <input type="text" name="customdirectory" value="<?php if(isset($_POST['customdirectory'])) echo $_POST['customdirectory']; ?>" />
        </div>
        <div class="option" id="regex">
            <label>Phân biệt hoa thường: </label>
            <span>Có</span><input type="radio" value="1" name="regex" <?php if(isset($_POST['regex']) && !$_POST['regex']){ ?><?php } else{ ?>checked="checked"<?php } ?> />
            <span>Không</span><input type="radio" value="0" name="regex" <?php if(isset($_POST['regex']) && $_POST['regex']){ ?><?php } else{ ?>checked="checked"<?php } ?> />
        </div>
        <input type="submit" value="Tìm Kiếm" name="submit" />
    </form>
    <hr />
    <?php if(isset($results)){ ?>
        <h2>Kết Quả Tìm Kiếm</h2>
        <?php foreach($results as $key => $result){ ?>
            <div id="file_<?php echo $key; ?>">
            <div class="heading" onclick="toggle_line('<?php echo $key; ?>')" ><?php echo $result['dir']; ?> - <span class="count-rs">(<?php echo count($result['lines']); ?>)</span></div> 
            <div id="line_<?php echo $key; ?>">
                <ul>
                    <?php foreach($result['lines'] as $line){ ?>
                        <?php if(trim($line) != ''){ ?>
                        <li><xmp><?php echo substr($line, 0, 100); ?></xmp></li>
                        <?php } ?>
                    <?php } ?>
                </ul>    
            </div> 
            </div> 
        <?php } ?>
    <?php } ?>
   <footer style="background: none repeat scroll 0% 0% rgb(37, 124, 178); position: fixed; right: 19px; bottom: 24px; width: 14%; border-radius: 71px; margin: 0px; padding: 0px; height: 86px;">
        <h1 style="text-align: center;"><a  style="color: white; text-decoration: none; font-size: 25px;" href="http://buivanhieu.wordpress.com/" />Bùi Văn Hiếu</a></h1>
   </footer> 
</body>
</html>