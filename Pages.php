<?php

class Pages extends CI_Controller {

	function __construct()
	{
		master 1

		parent::__construct();
		$this->load->library("session");
	}

	public function index()
	{
		echo '<a href="/pages/auth/">Войти</a><br><br>';
		echo '<a href="/pages/reg/">Зарегистрироваться</a>';
        }

	public function auth()
	{
		$this->load->library("session");
		$this->load->database();
		if(isset($_POST) && count($_POST) > 0 && $this->login())
		{
		
			print_r($_SESSION);

		}
		else
		{
			echo '<html>
			Войти
			<form action="/pages/auth/" method="post">
				login<br>
				<input type="text" name="login" value="">
				<br>
				<br>

				pass<br>
				<input type="password" name="pass" value="">
				<br><br>
				<input type="submit" value="авторизация">
			</form>
			</html>';
		}
	}

	public function reg()
	{
		$this->load->database();
		if(isset($_POST) && count($_POST) > 0 )
		{
			if($this->registration())
			{
				echo 'Вы успешно зарегистрировались';
			}
		}
		echo '<html>
		Регистрация
		<form action="/pages/reg/" method="post">
			login<br>
			<input type="text" name="login" value="">
			<br>
			<br>

			pass<br>
			<input type="password" name="pass" value=""><br><br>

			Confirm pass<br>
			<input type="password" name="conf_pass" value="">

			
			<input type="submit" value="Зарегистрироваться">
		</form>
		</html>';
	}

	private function login()
	{
		$query = $this->db->query("SELECT * FROM users
			WHERE
				login = ".$this->db->escape($_POST['login'])."
				AND 
				pass = '".md5($_POST['pass'])."'"
				);

		if(count($query->result()) > 0)
		{
			$this->session->set_userdata($_POST['login'], $query->result());
			return true;
		}
		return false;
	}

	private function registration()
	{
		if(!isset($_POST['login']) || !isset($_POST['pass']) || !isset($_POST['conf_pass']))
		{
			return false;
		}
		if($_POST['pass'] != $_POST['conf_pass'])
		{
			return false;
		}

		$query = $this->db->query("SELECT * FROM users
			WHERE
				login = ".$this->db->escape($_POST['login']));

		if($query->result && count($query->result()) > 0)
		{
			return false;
		}
		else
		{
			$data = array();
			$data['login'] = $this->db->escape_str($_POST['login']);
			$data['pass'] = md5($_POST['pass']);

			$this->db->insert('users', $data);
			if($this->db->insert_id())
			{
				return true;
			}
		}
		return false;
	}
}