<?php
/**
 * jQuery Mobile用の画面に表示させるためフック
 *
 * @author	Lee JunHo
 * @license	MIT License
 */

/*
 * Copyright (c) 2013 Lee JunHo
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy,
 * modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software
 * is furnished to do so, subject to the following conditions:

 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

 */

// application/hooks/DisplayHook.php
class DisplayHook {
	public function captureOutput() {
		$this->CI =& get_instance();
		$output = $this->CI->output->get_output();

		if ( ENVIRONMENT == 'development') {
			$this->CI->output->enable_profiler(TRUE);

			$this->CI->load->library('profiler');

			// TODO
// 			if ( ! empty($this->_profiler_sections))
// 			{
// 				$this->CI->profiler->set_sections($this->_profiler_sections);
// 			}

			if (preg_match_all("/<div data-role=[\'\"]footer[\'\"].*?>(.*?)<\/div>/is", $output, $matches)) {
				$tmp = array();
				foreach($matches[1] as $value) {
					$tmp[] = $value;
					$ok = false;

					foreach($tmp as $compare) {
						if (count($tmp) == 1 || $compare !== $value) {
							$ok = true;
						}
					}

					if ($ok) {
						$output = preg_replace("/".preg_quote($value, '/')."/is", $value.$this->CI->profiler->run(), $output);
					}
				}
			}
			else {
				$output .= $this->CI->profiler->run();
			}

		}

		// PHPUnit用
		if ($this->CI->config->item('phpunit') == FALSE) {
			echo $output;
		}
	}
}