<?PHP
	class getNextNum{
	
		public function processCurrNum($currNum) {
			$this->firstSubCntr = substr($currNum, 1, 10);
			$this->secondSub = substr($currNum, 11, 2);
			$this->thirdSub = substr($currNum, 13, 2);
			
			$this->outFirstSub = substr($currNum, 0, 11) + 1;
			$this->outFirstSubCntr = (int)substr($currNum, 1, 10) + 1;
			$this->outSecondSub = $this->secondSub;
			$this->outThirdSub = $this->thirdSub;
			
			
			
			if($this->modFunc(1000, $this->firstSubCntr)) {
				$this->outSecondSub = $this->outFirstSubCntr/1000;
				$this->outThirdSub = 1;
			}
			else {
				if($this->modFunc(100, $this->firstSubCntr)) {
					$secondSub = 0;
					
					if($this->modFunc(1000, $this->firstSubCntr)) {
						
					}
				
					$this->outSecondSub = $secondSub;
					$this->outThirdSub -= 8;
				}
				else {
					if($this->modFunc(10, $this->firstSubCntr)) {
						$this->outSecondSub -= 8;
						$this->outThirdSub -= 9;
					}
					else {
						$this->outSecondSub ++;
						$this->outThirdSub ++;
					}
				}
			}
			
			return $this->outFirstSub.str_pad($this->outSecondSub, 2, "0", STR_PAD_LEFT).str_pad($this->outThirdSub, 2, "0", STR_PAD_LEFT);
		}
		
		public function modFunc($mod, $cntr) {
			$result = false;
			
			if($mod == 10) {
				$rem = 9;
			}
			else if($mod == 100) {
				$rem = 99;
			}
			else if($mod == 1000) {
				$rem = 999;
			}
	
			if($cntr % $mod == $rem) {
				$result = true;
			}
			
			return $result;
		}
		
		//10; 10,000; 10,000,000
		//100; 100,000; 100,000,000
		//1,000; 1,000,000; 1,000,000,000
	}
?>