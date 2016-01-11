<?php

namespace App\Controller;

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Review Controller Provider
 *
 */
class ReviewController  {

     private $message;
    /**
     * Index Action
     * 
     * @param Application $app
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
	 public function indexAction(Application $app)
    {
		$this->message =0;
        return $app['twig']->render('addreview.twig', array('message' => $this->message));
    }
	 
	 
	 
	  /**
     * Add Review Action
     * 
     * @param Application $app
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function AddReviewAction(Application $app)
    {
		$request = $app['request'];
	    $data['autor'] = $request->get('autor');
		$data['text'] = $request->get('text');
		
       if($data['autor'] == NULL || $data['text'] == NULL || strip_tags($data['autor'])!=$data['autor'] || strip_tags($data['text'])!=$data['text'] ){
		   $message = 1;
		   return $app['twig']->render('addreview.twig', array('message' => $message));
	   }
	    else {
			$data['date'] = date("Y-m-d");
			$data['ip_address'] =  $_SERVER["REMOTE_ADDR"];
            
			$model  = new \App\Model\ReviewModel($app['db']);
			$model->addReview($data);
			
			$message = 2;
			return $app['twig']->render('addreview.twig', array('message' => $message));
		}
		
    }

}