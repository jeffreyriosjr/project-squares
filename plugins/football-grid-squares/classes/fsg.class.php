<?php
class football_squares{
    

    
		public $rows = 10;
		public $cols = 10;

		function write($id,$value){
			$data = $this->data();
            $data[$id] = $value;
			$write = serialize($data);
			file_put_contents($this->data, $write);
		}
		
		function remove($id,$value){
			$data = $this->data();
			unset($data[$id]);
			$write = serialize($data);
			file_put_contents($this->data, $write);
		}
		
		function data(){
		    $file = $this->data;

		    if (file_exists( $file)) {
			    $data = file_get_contents( $file);
		    } else {
			    file_put_contents($file, serialize(array()));
			    $data = file_get_contents( $file);
		    }
  
 		    $array = unserialize($data);
		 
		    return $array;
		}
		
		function ndata(){
		    $file = $this->ndata;

		    if (file_exists( $file)) {
			    $ndata = file_get_contents( $file);
		    } else {
			    file_put_contents($file, serialize(array()));
			    $ndata = file_get_contents( $file);
		    }
  
 		    $n_array = unserialize($ndata);
		 
		    return $n_array;
		}
		
		function form($id,$entry = ''){
			if($entry != ''){
				$value = $entry ;
			}else{
				$value = $_SESSION['id_name'] ;
			}
			
            $page_link = get_permalink();
            
            $h .= '<div class="modal fade fgs-modal" id="fgs_registerModal_'.$id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="fgs_registerModalLabel">Register Square #'.$id.'</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="fgs_register_form" action="'.$page_link.'" method="post">
			                    <input type="hidden" name="id" value="'.$id.'">
			                    <label>Name:</label> <input type="text" name="id_name" value="'.$value.'" style="width:100%">
			            </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
                            if($this->auth() == true){
                                $h .= '<input class="btn bg-danger txt-white fgs-frm-btn" type="submit" name="clear" value="Clear">';
                            };
                            $h .= '<input class="btn bg-primary txt-white fgs-frm-btn" type="submit" name="save" value="Save">
                            </form>
                        </div>
                    </div>
                </div>
            </div>';
			
			return $h;
		}
		
		function build(){
		    
			if($_POST['save'] != ''){
				$this->write(intval($_POST['id']),sanitize_text_field($_POST['id_name']));
				$_SESSION['id_name'] = sanitize_text_field($_POST['id_name']);
			}
			
			if($_POST['clear'] != ''){
				$this->remove(intval($_POST['id']),sanitize_text_field($_POST['id_name']));
			}
			
			if($_POST['generate'] != ''){
			    $num_top = range(0, 9);
                shuffle($num_top);
                
                $num_left = range(0, 9);
                shuffle($num_left);
                
                $data = $this->ndata();
			    $data[0] = $num_top;
			    $data[1] = $num_left;;
			    $write = serialize($data);
			    file_put_contents($this->ndata, $write);
			}
			

			$columns = (100/($this->cols+1));
			$data = $this->data();
			$ndata = $this->ndata();
			$num_top = $ndata[0];
			$num_left = $ndata[1];
			
            $h = $this->admin();
				
			$h .='<style type="text/css">
				.fgs-label-col,.square_col{width:'.$columns.'%}
			</style>
					  
			<div class="fgs-team-one">'.$this->team_one.'</div>
			<div class="fgs-team-two">'.$this->team_two.'</div>
					  
			<div class="fgs-squares-container">';
			
			$cols = 0;
				
			$h .= '<div class="square_row">
			    <div class="square_col">
				    <div class="fgs-label-inside fgs-blank"></div>
				</div>';
				
			for($c=0; $c< $this->cols; $c++){
				$h .= '<div class="square_col">
				    <div class="fgs-label-inside"><span class="fgs-num">'.$num_top[$c].'</span></div>
				</div>';
			}
				    
			$h .= '</div>';
						
			$abs = 0;
					
			for($i=0; $i< $this->rows; $i++){
				if($i != 0){
					$abs += $this->cols;
				}
				$cols = 0;
				
				$h .= '<div class="square_row">
				    <div class="square_col">
				        <div class="fgs-label-inside"><span class="fgs-num">'.$num_left[$i].'</span></div>
				    </div>';
				
				for($c=0; $c< $this->cols; $c++){
					$cols++;
					$num = $cols + $abs;
								
					if($data[$num] == ''){
						$link = '<button type="button" class="btn btn-outline-success fgs-btn d-print-inline-block" data-toggle="modal" data-target="#fgs_registerModal_'.$num.'" title="Register #'.$num.'">'.$num.'</button>';	
					}else{
						if($this->auth() == true){
						    $link = '<button type="button" class="btn btn-success fgs-btn-picked d-print-inline-block" data-toggle="modal" data-target="#fgs_registerModal_'.$num.'" title="Change or Clear #'.$num.'"><span class="fgs-name">'.$data[$num].'</span></button>';
						}else{
						    $link = '<button type="button" class="btn btn-success fgs-btn-picked d-print-inline-block" title="'.$data[$num].'"><span class="fgs-name">'.$data[$num].'</span></button>';
						}
							
					}
								
					$h .= '<div class="square_col">
						<div class="fgs-col-inside">'.$link .''.$this->form($num,$entry).'</div> 
						</div>';
				}
						
				$h .='<div style="clear:both"></div></div>';
						
			}
			
			$h .= $this->stat_data();
			
			$h .= '</div>';
			
			return $h;			
		}
		
		
		function stat_data(){
			
			$data = $this->data();
			$total = count($data);
			$totals = array_count_values($data);
			$left = ($this->rows * $this->cols) - $total;
			$total_squares .='<ul>';
			foreach($totals as $name => $times){
					
				$total_price = $times * $this->price;
				$total_squares .= '<li>'.$name.' has  '.$times.' squares '.$this->currency_symbol.''.$total_price.'</li>';
				
			}
			$total_squares .='</ul>';
			
			$h .='<div class="fgs-stats fgs-no-print">

			
			    <br/>
			    <h2>Stats</h2>
			    <div class="progress fgs-progress">
                    <div class="progress-bar fgs-progress-bar" role="progressbar" style="width: '.$total .'%;" aria-valuenow="'.$total .'" aria-valuemin="0" aria-valuemax="100">'.$total .'% Complete, only '.$left.' squares left. </div>
                </div>

			    '.$total_squares.'	
				
			</div>';
			
			return $h;
		}
		
		function auth(){
			global $current_user;
		    global $post;
	        get_currentuserinfo();
 
	        if (is_user_logged_in() && $current_user->ID == $post->post_author) {
				    return true;
				}else{
				    return false;	
				}
		}
		
		function admin(){
 
	        if($this->auth() == true){
	            $h .='<div class="fgs-generate fgs-no-print">
			        <form action="'.$page_link.'" method="post">
			            <input class="btn btn-outline-primary" type="submit" name="generate" value="Generate Numbers">
			        </form>
			    </div>';
			
			    return $h;
	        }
			
		} 
	
}
?>