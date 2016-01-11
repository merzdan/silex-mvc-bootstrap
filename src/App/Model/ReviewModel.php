<?php

namespace App\Model;


use Doctrine\DBAL\Query\QueryBuilder;
/**
 * Review Model
 *
 */
class ReviewModel {

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
     * 
     */
	public function getAllReviews(){
		
	 $qb = new QUeryBuilder($this->db);
		
	  $sql = "SELECT * FROM reviews";
	  
	 $stmt = $qb->select('*')
            ->from('reviews')
            ->execute();
	  
      $reviews = $stmt->fetchAll();
	  
	  return $reviews;
		
	}
	
	  /**
     * Get Review By Id
     * 
     * @param $id
     */
	public function getReviews($id){
		
	  $sql = "SELECT * FROM reviews WHERE id=?";
      $reviews = $this->db->fetchAll($sql);
	  
	  return $reviews;
		
	}
	
	
	
	  /**
     *  Add Review
     * 
     * @param $data array
     */
	public function addReview($data = array()){
		
	 $qb = new QUeryBuilder($this->db);
		
	 $qb->insert('reviews')
             ->setValue('autor', '?')
             ->setValue('text', '?')
			 ->setValue('ip_address', '?')
             ->setValue('date', '?')
             ->setParameter(0, $data['autor'])
             ->setParameter(1, $data['text'])
	         ->setParameter(2, $data['ip_address'])
             ->setParameter(3, $data['date'])
			 ->execute();
	  
	}

}