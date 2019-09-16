<?php
return array (
		//应用ID,您的APPID。
		'app_id' => "asdfasdfasdf",

		//商户私钥
		'merchant_private_key' => "MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCY7a6vuC6lcKt/SZkp3uzkzeN9nbdZ4VVykMHkDKCLn45swQqJMhRqTllJi+Nr7JQuadpRWYmU8QBUrkUdLARuv57LK0a6h7BnzJXCDg7rhmLYT7xZKHhmJPZExczfgdprpfmjLCt7NcVYb1zufwkpDQa/AWb/6iUG97RiOLIKL59m+qLyQPb4FxfSnlg08c8FssGViEuDG9QMzbktR1x/7Fm9k6IU/p22gj6ZVa34MS6N5qZDanoBkBg4xCIdNZW9z4O4NIc5QZvAqyqXO/uS3HGux7ZZm+X/3L4+qgbwzVFqgber+rI+3x4ftK7f/nAQqB8YxmNm4s7EAnoQ+eLpAgMBAAECggEAFX5abfIKBFCYmfRDJaJiUyoozg/rpCNKeiakSaQIsHcFcn2TOIMkYWiPngmeNh3zGa7g80wNTTSuIji4GAiGuvYNoGp9r1vAzGXxqmuFZ07k95gFVdadTWXmgvX1/HfotfaG5osftVpyWSKVwiNyqyVcjF3rFw7Wk2sfHCrxwwKEniHkfjwxZ/E6oUQArxcT9oiwVnQaWelGhqJSz1sAIg/G7WWXdC3wksg/X4dzq0uScFeYJcRNj0zGiJ0IXwzqErx/Wi40k44qFMQC3Zhh6osVH2fkCn3Z1S5+NRkhvl9+RXNK0oNlMiXHG1flZvHszgSiLyZ+wPwzoQ44RMp8bQKBgQDZGVMvqgKhIPyuPFjCqA7clo16RBXZkQdWQD6/Akfn45dmeOL3yNqFSZhHc0wzO4fc5iitCjNcmAw3K421xfAYE/QtU7ur2kmKt94F1vZGFy6qg5rjmxs2YSpyn+Fc80UuX3qtQJb1P2HLwncB3PAV5WaOu3N+2dA5FgpN2K94iwKBgQC0VMFAo9uDjkSrYwETgUaVOksUl5ycvwjx+cgbY6w66TWZqMkZa2zAuAsBvm4coysssXDczyUt7qI0q2TPVjUW82ibKTPFSET6vxeVRHncpTON5twgzbx1nYUmI5iyrO0I61sUJwNcWN9BtoLxVMknq8ISjWL1r5eyBWjyd03M2wKBgD4LmNSkd//o145MPOnU8fplJKXu03fMlRY3YjdgxYAmcVyd6+/4/Urej6DL+NkjiF6/gJyDr84kvkj+L4/ltAqNmVli9t3UkvQMPG3a06OQeIvO9PNbntlZx7Hes5/G/tyT+RGOxhXtOVvsheqIZC091KOyOv3j7jiCbgt1hCClAoGAUDpo4/5CeiwAZxOb9faM1XVi092D4sSnESikm3LjvC3nF97c4T9G2hLHatYzHPCHE9I5uTM7gkzpw28BYbEj23sdbfKNwtadQcVkk5csdDrXTemIw9tkXhtfkpFBrTR8HHzBP5z/xMURRqYRaZbkC49Bv4lBnrapUZ1QjJcBeQMCgYBDj6oKvUApc5kn++HEjBLUlBD2sUtuCJ3+GH2rYwSxds3mmnubZmcJiIGbaf0BW65cKKDoD7zFGMPL3+TxLmUZ2r3N6oxB8RHsa3lc5CX9wH4I+2jWJ8xP2AgxwqDybZqDpV6PkeQz3j+heXbM5Ud2bOu7kyd8ItRlphYjGwWLeg==",
		
		//异步通知地址
		'notify_url' => "http://外网可访问网关地址/alipay.trade.page.pay-PHP-UTF-8/notify_url.php",
		
		//同步跳转
		'return_url' => "http://外网可访问网关地址/alipay.trade.page.pay-PHP-UTF-8/return_url.php",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "asdfasdf",
);