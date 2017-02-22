<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Model_getvalues extends CI_Model {
    
    function getTableRows3($table, $where, $whereVal, $orderby, $orderVal = "desc", $limit = 0) {
        $this->db->select('*');
        $this->db->where($where, $whereVal);
        $this->db->from($table);
        $this->db->order_by($orderby, $orderVal);
        if ($limit != 0) {
            $this->db->limit($limit);
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }
    
    function getTableRows($table, $where, $whereVal, $orderby, $orderVal = "desc", $limit = 0) {
        $this->db->select('*');
        $this->db->where($where, $whereVal);
        $this->db->from($table);
        $this->db->order_by($orderby, $orderVal);
        if ($limit != 0) {
            $this->db->limit($limit);
        }
        $query = $this->db->get();
        return $query->result();
    }
    
}