<?php

class PIHG_Seed_Info extends CMB_Field {

	public function html() {
		echo( "<table>\n" );
		echo( "\t<thead>\n" );
		echo( "\t\t<tr>" );
		echo( "<th>Column 1</th>" );
		echo( "<th>Column 2</th>" );
		echo( "</tr>\n" );
		echo( "\t</thead>\n" );
		echo( "\t<tbody>\n" );
		echo( "\t\t<tr>\n" );
		echo( "\t\t\t<td><input type='text' name='{$this->name}[col1]' /></td>\n" );
		echo( "\t\t\t<td><input type='text' name='{$this->name}[col2]' /></td>\n" );
		echo( "\t\t</tr>\n" );
		echo( "\t</tbody>\n" );
		echo( "</table>\n" );
	}

	public function parse_save_value() {
		$this->value = serialize( $this->value );
	}
}
