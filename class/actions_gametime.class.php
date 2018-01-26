<?php
/* <one line to give the program's name and a brief idea of what it does.>
 * Copyright (C) 2015 ATM Consulting <support@atm-consulting.fr>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * \file    class/actions_gametime.class.php
 * \ingroup gametime
 * \brief   This file is an example hook overload class file
 *          Put some comments here
 */

/**
 * Class ActionsGameTime
 */
class ActionsGameTime
{
	/**
	 * @var array Hook results. Propagated to $hookmanager->resArray for later reuse
	 */
	public $results = array();

	/**
	 * @var string String displayed by executeHook() immediately after return
	 */
	public $resprints;

	/**
	 * @var array Errors
	 */
	public $errors = array();

	/**
	 * Constructor
	 */
	public function __construct()
	{
	}

	/**
	 * Overloading the doActions function : replacing the parent's function with the one below
	 *
	 * @param   array()         $parameters     Hook metadatas (context, etc...)
	 * @param   CommonObject    &$object        The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param   string          &$action        Current action (if set). Generally create or edit or null
	 * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
	 * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
	 */
	function doActions($parameters, &$object, &$action, $hookmanager)
	{
		$error = 0; // Error counter
		$myvalue = 'test'; // A result value

		//print_r($parameters);
		//echo "action: " . $action;
		//print_r($object);

		if (in_array('somecontext', explode(':', $parameters['context'])))
		{
		  // do something only for the context 'somecontext'
		}

		if (! $error)
		{
			$this->results = array('myreturn' => $myvalue);
			$this->resprints = 'A text to show';
			return 0; // or return 1 to replace standard code
		}
		else
		{
			$this->errors[] = 'Error message';
			return -1;
		}
	}

    function formObjectOptions($parameters, &$object)
    {
        global $db, $langs;
        $res = $db->query("SELECT avg(time) FROM llx_gametime WHERE fk_contact=" . (int)$object->id);
        $time = $res->fetch_row();

        echo '<tr>
		  	<td>'.$langs->trans("tempsJeux").'</td>
			<td colspan="'.$parameters['colspan'].'">~ '.round ($time[0], 0).' h</td>
		  </tr>';

        $res = $db->query("SELECT MAX(time), title FROM llx_gametime WHERE fk_contact=" . (int)$object->id ." GROUP BY title, time HAVING time=MAX(time)");
        $time = $res->fetch_row();

        echo '<tr>
		  	<td>'.$langs->trans('maxTime').'</td>
			<td colspan="'.$parameters['colspan'].'">'.$time[1].': '.$time[0].'h</td>
		  </tr>';
    }
}