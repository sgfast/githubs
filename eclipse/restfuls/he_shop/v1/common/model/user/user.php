<?php

/**
 * Main */
const Er_User_Main = '{
    "_id": "",
	"aid": 100,
	"openid": "",
	"nickname": "",
	"mobile": "",
	"email": "",
	"thumb": "",
	"point": 0,
	"cash": 0,
	"register": 0,
	"readtime":	0,
	"sign":{
		"time":	0,
		"count":5
	},
	"login":{
		"first": 0,
		"last": 0,
		"count": 0,
		"item": 0,
		"amount": 0
	},
	"buy":{
		"first": 0,
		"last": 0,
		"count": 0,
		"item": 0,
		"amount": 0
	},
	"fans":{
		"did": "",
		"start": 0,
		"end": 0
	},
	"distr":{
		"fans": 0,
		"level": 0,
		"code":	"",
		"realname": "",
		"idcard": "",
		"paytype": "",
		"account": "",
		"bank": "",
		"bankid": ""
	},
	"gift": [],
	"feedback": []
}';

/**
 * Gift */
const Er_User_Gift = '{
	"tid": "",
	"name": "",
	"status": 0
}
';

/**
 * Feedback */
const Er_User_Feedback = '{
    "content": "",
    "answer": "",
    "time": {
        "submit": 0,
        "answer": 0
    }
}
';

/**
 * Er_User */
$Er_User = new ModelClass();
$Er_User->Main = Er_User_Main;
$Er_User->Feedback = Er_User_Feedback;

/** prototype
	"_id": "",
	"aid": 100,
	"openid": "",
	"nickname": "",
	"mobile": "",
	"email": "",
	"thumb": "",
	"point": 0,
	"cash": 0,
	"register": 0,
	"feedback": [{
		"content": "",
		"answer": "",
		"time": {
			"submit": 0,
			"answer": 0
		}
	}]
}
*/

?>