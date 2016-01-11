<?php

namespace App\Controller;

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Index Controller Provider
 *
 */
class IndexController {

    
    /**
     * Index Action
     * 
     * @param Application $app
     * @return mixed
     */
    public function indexAction(Application $app)
    {
		
		$model = new \App\Model\IndexModel($app['db']);
        $sort = 0;
		//$likes = $model->addLike(2, 13);
	    $request = $app['request'];
	    if($request->get('sort')) $sort = $request->get('sort');
		if($request->get('id_like')) {
			$id = $request->get('id_like');
			$likes = $request->get('likes');
			if($model->validLike($id)){
				$likes = $model->addLike($id, $likes);
				return json_encode(array('id' => $id, 'error' => false, 'likes' =>$likes));
			} else return  json_encode(array('id'=> $id, 'error' => true));
		}
		else return $app['twig']->render('index.twig' , array( 'sort' => $sort, 'reviews' => $model->getAllReviews($sort)));
    }
	
	   /**
     * Review Action
     * 
     * @param Application $app
     * @return mixed
     */
	 public function ReviewAction(Application $app)
    {
		
		$model = new \App\Model\IndexModel($app['db']);
		$request = $app['request'];
		$id = $request->get('id');
        return $app['twig']->render('review.twig' , array( 'review' => $model->getReview($id)));
    }
	
	
}