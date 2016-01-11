<?php

namespace App\Model;


use Doctrine\DBAL\Query\QueryBuilder;
/**
 * Index Model
 *
 */
class IndexModel {

    protected $db;

    /**
     * Database Connect
     * 
     * @param $db
     */
    function __construct($db)
    {
        $this->db = $db;
    }
	
	
	  /**
     * Get All Review
     * 
     * @param $sort
	 * @return $review array
     */
	
	public function getAllReviews($sort = 0){
		
	    $qb = new QUeryBuilder($this->db);
	  
	     switch($sort){
  		    case 1: $stmt = $qb->select('*')
                 ->from('reviews')
			     ->orderBy('date', 'DESC')
                 ->execute();
				 break;
	     case 2: $stmt = $qb->select('*')
                 ->from('reviews')
			     ->orderBy('likes', 'DESC')
                 ->execute();
				 break;
	     default:  $stmt = $qb->select('*')
                 ->from('reviews')
                 ->execute();
	    }
		
	  $reviews = $stmt->fetchAll();
	  
	  return $reviews;
	}
	
	
	  /**
     * Get Review By Id
     * 
     * @param $id
	 * @return $review
     */
	public function getReview($id){
		
	  $qb = new QUeryBuilder($this->db);
		
      $stmt = $qb->select('*')
                 ->from('reviews')
				 ->where('id=' . $id . '')
                 ->execute();
		
       $review = $stmt->fetchAll();	

       $qbn = new QUeryBuilder($this->db);
	   
	   $stmt = $qbn->select('id')
                 ->from('reviews')
				 ->where('id > (SELECT id FROM `reviews` WHERE id = ' . $id . ')')
				 ->orderBy('id', 'ASC')
				 ->setMaxResults('1')
                 ->execute();	 		
		$next = $stmt->fetchAll();

       $qbp = new QUeryBuilder($this->db);	   
	   
	   $stmt = $qbp->select('id')
                 ->from('reviews')
				 ->where('id < (SELECT id FROM `reviews` WHERE id = ' . $id . ')')
				 ->orderBy('id', 'DESC')
				 ->setMaxResults('1')
                 ->execute();
		$prev = $stmt->fetchAll();
	
	  $review[0]['prev_id'] = $prev[0]['id'];
	  
	  $review[0]['next_id'] = $next[0]['id'];
	  
	  return $review[0];
		
	}
	
	
	/**
     * Add Like
     * 
     * @param $id, $likes
	 * @return $likes
     */
	public function addLike($id, $likes){
		
		$likes++;
		
		$qbr = new QUeryBuilder($this->db);
		
		$qbr->update('reviews')
                 ->set('likes', ''. $likes .'')
				 ->where('id='. $id . '')
                 ->execute();

		
        $qbl = new QUeryBuilder($this->db);		
		
		$qbl->insert('likes')
             ->setValue('id_review', $id)
			 ->setValue('ip_address', '"' . $_SERVER["REMOTE_ADDR"] . '"')
			 ->execute();

		
		return $likes;
	}
	
		/**
     * Valid Like
     * 
     * @param $id
	 * @return true or false
     */
	public function validLike($id){
		
		$qb = new QUeryBuilder($this->db);
		
		$stmt = $qb->select('*')
                 ->from('likes')
				 ->where('id_review=' . $id . ' AND ip_address="' . $_SERVER["REMOTE_ADDR"] . '"')
                 ->execute();
		
        if($stmt->fetchAll()) 
			return false;
		else 
			return true;
		
	}

}