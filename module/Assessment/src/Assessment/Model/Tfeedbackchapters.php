<?php
namespace Assessment\Model;

class Tfeedbackchapters
{
	public function exchangeArray($data)
	{
		       
    }
	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}