<?php

action("put,post", "users/eval" , function(){
	responseData(post());
	#responseData("sin auth rol");
} , "user");