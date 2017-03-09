<!--<?php
defined('IN_MET') or exit('No permission');
require $this->template('ui/head');
echo <<<EOT
-->
<script type="text/javascript" charset="utf-8">
    var phplang = '{$lang}';
</script>
<div id="elfinder"></div>
<!--
EOT;
require $this->template('ui/foot');
?>