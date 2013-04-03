<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * jQuery Mobile用のアプリケーションのプロファイリングライブラリ
 *
 * system/Profile.phpを継承し、runと_compile_session_dataをオーバライドする
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

/*
 * Codeigniter License
 *
 * @license		http://codeigniter.com/user_guide/license.html
 */

class MY_Profiler extends CI_Profiler {

	// --------------------------------------------------------------------

	public function __construct($config = array())
	{
		parent::__construct();
	}

	// --------------------------------------------------------------------

	/**
	 * Run the Profiler
	 *
	 * @return	string
	 */
	public function run()
	{
		$output = '';
// 		$output = '<div data-role="page" class="ui-page ui-body-c ui-page-active">';
// 		$output .= '<div data-role="header"><!-- header --></div><!-- /header -->';
// 		$output .= '<div data-role="content">';
		$output .= '<div class="codeigniter_profiler" style="clear:both;background-color:#fff;padding:10px;">';

		$fields_displayed = 0;

		foreach ($this->_available_sections as $section)
		{
			if ($this->_compile_{$section} !== FALSE)
			{
				$func = "_compile_{$section}";
				$output .= $this->{$func}();
				$fields_displayed++;
			}
		}

		if ($fields_displayed == 0)
		{
			$output .= '<p style="border:1px solid #5a0099;padding:10px;margin:20px 0;background-color:#eee">'.$this->CI->lang->line('profiler_no_profiles').'</p>';
		}

		$output .= '</div>';

// 		$output .= '</div><!-- /content -->';
// 		$output .= '</div><!-- /page -->';

		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Compile session userdata
	 *
	 * @return 	string
	 */
	private function _compile_session_data()
	{
		if ( ! isset($this->CI->session))
		{
			return;
		}

		$output = '<fieldset id="ci_profiler_csession" style="border:1px solid #000;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee">';
		$output .= '<legend style="color:#000;">&nbsp;&nbsp;'.$this->CI->lang->line('profiler_session_data').'&nbsp;&nbsp;(<span style="cursor: pointer;" onclick="var s=document.getElementById(\'ci_profiler_session_data\').style;s.display=s.display==\'none\'?\'\':\'none\';this.innerHTML=this.innerHTML==\''.$this->CI->lang->line('profiler_section_show').'\'?\''.$this->CI->lang->line('profiler_section_hide').'\':\''.$this->CI->lang->line('profiler_section_show').'\';">'.$this->CI->lang->line('profiler_section_show').'</span>)</legend>';
		$output .= "<table style='width:100%;display:none' id='ci_profiler_session_data'>";

		foreach ($this->CI->session->all_userdata() as $key => $val)
		{
			if (is_array($val) OR is_object($val))
			{
				$val = print_r($val, TRUE);
			}

			$output .= "<tr><td style='padding:5px; vertical-align: top;color:#900;background-color:#ddd;'>".$key."&nbsp;&nbsp;</td><td style='padding:5px; color:#000;background-color:#ddd;'>".htmlspecialchars($val)."</td></tr>\n";
		}

		$output .= '</table>';
		$output .= "</fieldset>";
		return $output;
	}

	// --------------------------------------------------------------------
}

// END CI_Profiler class

/* End of file Profiler.php */
/* Location: ./apllication/libraries/MY_Profiler.php */