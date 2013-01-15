<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class waktu extends CI_Controller {

	public function index($zone,$state,$year,$month=NULL)
	{
		
		if(!empty($month)){

			$item[] = $this->get_waktu($zone,$state,$year,$month);
		}
		else{

			for ($i=1; $i <= 12; $i++) { 
				$item[$i] = $this->get_waktu($zone,$state,$year,$i);
			}

		}
		

		$data =  array(
			'zon' => $zone ,
			'state' => $state, 
			'year' => $year,
			'month' => $month,
			'takwim' => $item
			
		 );
		
		$data = array(
			"data" => $data
		);
	
		$this->output
	    ->set_content_type('application/json')
	    ->set_output(json_encode(array($data)));

	}

	private function get_waktu($zone,$state,$year,$bulan){
		$this->load->library('simple_html_dom');
		$html =  file_get_html("http://www.e-solat.gov.my/muatturun.php?zone=$zone&state=$state&jenis=year&lang=my&year=$year&bulan=$bulan");
		//$html =  file_get_html('http://www.e-solat.gov.my/waktusolat.php?zone=SGR04&state=Putrajaya&year=2013&jenis=year&bulan=02&LG=BM');

		$tbl_waktu_solat = $html->find('table',1);

		foreach ($tbl_waktu_solat->find('tr') as $td) {
			$item[] = array(
				'tarikh' => $this->data_format($td->find('td',0)->plaintext),
				'hari' =>  $this->data_format($td->find('td',1)->plaintext),
				'imsak' =>  $this->data_format($td->find('td',2)->plaintext),
				'Subuh' =>  $this->data_format($td->find('td',3)->plaintext), 
				'Syuruk' =>  $this->data_format($td->find('td',4)->plaintext),
				'Zohor' =>  $this->data_format($td->find('td',5)->plaintext),
				'Asar' =>  $this->data_format($td->find('td',6)->plaintext),
				'Maghrib' =>  $this->data_format($td->find('td',7)->plaintext),
				'Isyak' =>  $this->data_format($td->find('td',8)->plaintext)
			 ); 
		}
		//remove table header
		array_shift($item); 
		//clear memory
		$html->clear();
		return $item;

	}


	private function data_format($data){
		$data = trim($data);
		$data = str_replace(";",":", $data);
		return $data;
	}

}

/* End of file waktu.php */
/* Location: ./application/controllers/waktu.php */
