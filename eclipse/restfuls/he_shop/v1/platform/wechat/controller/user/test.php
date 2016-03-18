
<?php

?>
<script type='text/javascript' src='jquery.js'></script>
<script type='text/javascript'>
$(function()
{
	var page = 1;
	var timer = window.setInterval("Ajax('../../restful.php?master=user&controller=user.move&action=move')",31000);

	Ajax = function(url)
	{
		$.ajax({
			type: 'post',
			dataType: 'json',
			data: {page: page},
			url: url,
			success:function(data)
			{		
				document.write("第"+page+"页数据:</br>");	
				switch(data.return)
				{
					case 'OK':
						var start = (page-1)*500;
						var end = page*500;
						document.write(start + " ~ " + end + " 条执行:成功!</br>");
						if (page >= 79)
							window.clearInterval(timer); 
						page ++;
						break;
						
					default:
						var start = (page-1)*500;
						var end = page*500;
						document.write(start + " ~ " + end + " 条执行:失败!</br>");
						break;
				};
			},
			error:function()
			{
				var start = (page-1)*500;
				var end = page*500;
				document.write(start + " ~ " + end + " 条执行:失败!</br>");
				return false;
			}
		});
	}
	
});

</script>