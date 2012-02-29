<?php
/*
* Copyright (c) 2011 Litle & Co.
*
* Permission is hereby granted, free of charge, to any person
* obtaining a copy of this software and associated documentation
* files (the "Software"), to deal in the Software without
* restriction, including without limitation the rights to use,
* copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the
* Software is furnished to do so, subject to the following
* conditions:
*
* The above copyright notice and this permission notice shall be
* included in all copies or substantial portions of the Software.
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND
* EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
* OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
* NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
* HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
* WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
* FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
* OTHER DEALINGS IN THE SOFTWARE.
*/

require_once("../../simpletest/autorun.php");
require_once('../../simpletest/unit_tester.php');
require_once realpath(dirname(__FILE__)) . '/../../lib/LitleOnline.php';

class credit_FunctionalTest extends UnitTestCase
{
	function test_simple_credit_withCard()
	{
		$hash_in = array(
			'card'=>array('type'=>'VI',
					'number'=>'4100000000000001',
					'expDate'=>'1213',
					'cardValidationNum' => '1213'),
			'id'=>'1211',
			'orderId'=> '2111',
			'reportGroup'=>'Planets',
			'orderSource'=>'ecommerce',
			'amount'=>'123');

		$initilaize = &new LitleOnlineRequest();
		$creditResponse = $initilaize->creditRequest($hash_in);
		$response = XMLParser::get_node($creditResponse,'response');
		$this->assertEqual('000',$response);
	}

	function test_simple_credit_withpaypal()
	{
		$hash_in = array(
				'paypal'=>array("payerId"=>'123','payerEmail'=>'12321321',
 				"transactionId" => '123123'),
				'id'=>'1211',
				'orderId'=> '2111',
				'reportGroup'=>'Planets',
				'orderSource'=>'ecommerce',
				'amount'=>'123');

		$initilaize = &new LitleOnlineRequest();
		$creditResponse = $initilaize->creditRequest($hash_in);
		$message= XMLParser::get_attribute($creditResponse,'litleOnlineResponse','message');
		$this->assertPattern('/Error validating xml data against the schema/',$message);
	}
	
	function test_simple_credit_withlitleTxnId()
	{
		$hash_in = array('reportGroup'=>'planets','litleTxnId'=>'1234567891234567891');
	
		$initilaize = &new LitleOnlineRequest();
		$creditResponse = $initilaize->creditRequest($hash_in);
		$message= XMLParser::get_attribute($creditResponse,'litleOnlineResponse','response');
		$this->assertEqual("1",$message);
	}
	function test_paypal_notes()
	{
		$hash_in = array(
				'card'=>array('type'=>'VI',
						'number'=>'4100000000000001',
						'expDate'=>'1213',
						'cardValidationNum' => '1213'),
				'id'=>'1211',
				'payPalNotes'=>'hello',
				'orderId'=> '2111',
				'reportGroup'=>'Planets',
				'orderSource'=>'ecommerce',
				'amount'=>'123');
	
		$initilaize = &new LitleOnlineRequest();
		$creditResponse = $initilaize->creditRequest($hash_in);
		$response = XMLParser::get_node($creditResponse,'response');
		$this->assertEqual('000',$response);
	}
	function testamexAggregator()
	{
		$hash_in = array(
		  'amount'=>'2000',
	      'orderId'=>'12344',
	      'orderSource'=>'ecommerce',
	      'processingInstuctions'=>array('bypassVelocityCheck'=>'yes'),
	      'card'=>array(
	      'type'=>'VI',
	      'number' =>'4100000000000001',
	      'expDate' =>'1210'),
	      'amexAggregatorData'=>array('sellerMerchantCategoryCode'=>'1234','sellerId'=>'1234Id'));
	
		$initilaize = &new LitleOnlineRequest();
		$creditResponse = $initilaize->creditRequest($hash_in);
		$response = XMLParser::get_node($creditResponse,'response');
		$this->assertEqual('000',$response);
	}


}
