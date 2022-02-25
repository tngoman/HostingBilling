<?=sprintf("<form action=\"https://%s:%s/CMD_LOGIN\" class=\"direct_login\" method=\"post\">" . "<input type=\"hidden\" name=\"username\" value=\"%s\" />" . "<input type=\"hidden\" name=\"password\" value=\"%s\" />" . "</form>", $hostname, $port, $username, $authkey)?>
<script>
$(document).ready(function(){
     $(".direct_login").submit();
});
</script>