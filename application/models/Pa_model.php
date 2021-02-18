<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class pa_model extends CI_Model
{
    
    public function __construct()
    {
        parent::__construct();
        $this->table = 'videos';
    }
    
    public function insertVideo($data)
    {
        $this->db->insert($this->table, $data);
        return $this->getVideoById($this->db->insert_id());
    }

    public function getVideoList($uid)
    {
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get_where($this->table, array('Uid' => $uid));        
        return $query->result();
    }

    public function edit_video($id,$data)
    {
        $this->db->where('id', $id);
        $this->db->update('videos', $data);
        return true;
    }

    public function delete_video($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('videos');
        return true;
    }

    public function getVideoById($id)
    {
        $query = $this->db->get_where($this->table, array('id' => $id));
        return $query->result()[0];
    }

    public function insertPresentation($data)
    {
        $this->db->insert("presentations",$data);
        return $this->db->insert_id();
    }

    public function get_presentations_list($uid)
    {
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get_where("presentations", array('uid' => $uid));
        return $query->result();
    }

    public function delete_presentation($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('presentations');
        $this->db->where('presentation_id', $id);
        $this->db->delete('segments');
        $this->db->where('pid', $id);
        $this->db->delete('presentation-video');
        return true;
    }

    public function getPresentationById($id)
    {
        $query = $this->db->get_where('presentations', array('id' => $id));
        return $query->result()[0];
    }

    public function updatePresentation($data, $pid)
    {
        $this->db->where('id', $pid);
        $this->db->update('presentations', $data);
    }
    
    public function insertSegment($data)
    {
        $this->db->insert("segments",$data);
        return $this->db->insert_id();
    }
    
    public function updateSegment($data, $segid)
    {
        $this->db->where('id', $segid);
        $this->db->update('segments', $data);
        return true;
    }

    public function deleteSegment($pid, $segid)
    {
        $this->db->where('id', $segid);
        $this->db->delete('segments');
        $this->db->where('segid', $segid);
        $this->db->delete('presentation-video');
        return true;
    }

    public function updatePresentationVideo($data, $segid)
    {
        $this->db->where('segid', $segid);
        $this->db->update('presentation-video', $data);
        return true;
    }

    public function getSegmentById($pid, $segid)
    {        
        $query = $this->db->get_where("segments", array('presentation_id' => $pid, 'id' => $segid));
        return $query->result();        
    }

    public function getSegments($pid)
    {
        $query = $this->db->get_where("segments", array('presentation_id' => $pid));
        return $query->result();
    }
    public function insertPresentationVideo($data)
    {
        $this->db->insert("presentation-video",$data);
        return $this->db->insert_id();
    }

    public function updatePresentationTextContent($data,$id)
    {
        $this->db->where('id', $id);
        $this->db->update('presentations', $data);
        return true;
    }

    public function getVideosById($pid, $segid)
    {
        $this->db->select("*");
        $this->db->from("presentation-video");
        $this->db->where('pid', $pid);
        $this->db->where('segid', $segid);
        $this->db->join('videos', 'videos.id = presentation-video.vid');
        $this->db->group_by('vid');
        $query = $this->db->get();
        return $query->result(); 
    }

    public function getPreVideos($pid)
    {
        $this->db->select("*");
        $this->db->from("presentation-video");
        $this->db->where('pid', $pid);
        $this->db->join('videos', 'videos.id = presentation-video.vid');
        $this->db->group_by('vid');
        $query = $this->db->get();
        return $query->result(); 
    }
}