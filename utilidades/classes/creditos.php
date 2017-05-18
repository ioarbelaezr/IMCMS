<?php

	class creditos{

		var $balance=0;
		var $sistema;
		var $idUsuario;
		var $cloud = __sistema;

		function __construct($idUsuario=false , $sistema="default"){
			if($idUsuario==false){
				$idUsuario = $_SESSION['data_login']['id'];
			}
			$this->sistema = $sistema;
			$this->idUsuario = $idUsuario;
		}

		function agregarMovimiento($monto=0, $detalles="",$tipoTransaccion=1){
			if($monto==0){return false;}
			/* Si es una transaccion de retiro primero verificamos si tiene saldo suficiente en su balance */
			if($tipoTransaccion==0){
				$balance = $this->balance();
				if( $balance['balance']< $monto ){
					return false;
					exit;
				}
			}

			$sql = sprintf("INSERT INTO `creditos_flujos` (`cloud`,`sistema`, `id_login`, `tipo_transaccion`,`monto`, `descripcion`) VALUES (%s, %s, %s, %s,%s, %s)",
				varSQL($this->cloud),
				varSQL($this->sistema),
				varSQL($this->idUsuario),
				varSQL($tipoTransaccion),
				varSQL($monto),
				varSQL($detalles)
			);
			$r = consulta($sql);

			$resultado = ( $r['filas_afectadas'] == -1 )? false : true ;
			return $resultado;
		}

		/**
		* Obtener el listado de movimientos del sistema actual y de $user o todos si este vale false
		*/
		function getMovimientos($user=false, $filtros=false, $cuantosxpagina=false, $parametroUrl="prm", $urlPaginacion="/es/{sistema}/{page}"){

			$urlPaginacion = str_replace("{sistema}", $this->idSistema, $urlPaginacion);

			if($user===false){
				$sql = sprintf("SELECT * FROM `pagegear_cashflow` WHERE `cloud`=%s AND `sistema`=%s AND",
					getSQLV($this->idPGE),
					getSQLV($this->idSistema)
				);
			}else{
				$sql = sprintf("Select * FROM `pagegear_cashflow` WHERE `id_pge`=%s and `id_sistema`=%s and `id_usuario`=%s",
					getSQLV($this->idPGE),
					getSQLV($this->idSistema),
					getSQLV($this->idUsuario)
				);
			}

			if($filtros!==false){
				$sqlFilters = "";
				if(is_array($filtros))
				foreach($filtros as $k=>$v){
					if($k=="sql"){
						$sqlFilters .= $v;
					}else{
						$sqlFilters .= " `{$k}`= ".getSQLV($v);

					}
				}
				$sql .= $sqlFilters;
			}

			$sql .= " order by fecha desc";

			if($cuantosxpagina!==false){
				$total_registros=getCampoSqlQueryHD(str_replace("Select *", "Select count(*)", $sql));
				$this->paginador = new Paginador($parametroUrl);
				$this->paginador->set_page_data($urlPaginacion,$total_registros, $cuantosxpagina ,5);
				$sql = $this->paginador->get_limit_query($sql);
			}

			$r = HDConector($sql, true);

			return ( $r==false )? false : $r['datos'] ;
		}

		/*
		 * Obtener la informacion de un movimiento especifico, valida si es del usuario actual.
		 */
		function getMovimiento($idMovimiento){
			$sql = sprintf("Select * FROM `pagegear_cashflow` WHERE `id_pge`=%s and id=%s",
				getSQLV($this->idPGE),
				getSQLV($idMovimiento)
			);
			$r = HDConector($sql, true);
			return ( $r==false )? false : $r['datos'][0] ;
		}
		
		/**
		* Obtener balance actual del usuario actual o cualquier otro
		*/
		function balance($usuario = false){
			if($usuario==false){ $usuario = $this->idUsuario; }

			$depositos = obtener_campo(sprintf("SELECT SUM(`monto`) AS total FROM `creditos_flujos` WHERE `cloud`=%s AND `sistema`=%s AND `id_login`=%s AND `tipo_transaccion` = 1",
				varSQL($this->cloud),
				varSQL($this->sistema),
				varSQL($usuario)
			));
			$retiros = obtener_campo(sprintf("SELECT SUM(`monto`) AS total FROM `creditos_flujos` WHERE `cloud`=%s AND `sistema`=%s AND `id_login`=%s AND `tipo_transaccion` = 0",
				varSQL($this->cloud),
				varSQL($this->sistema),
				varSQL($usuario)
			));
		


			return array(
				"depositos"		=>		$depositos+0,
				"retiros"		=>		$retiros+0,
				"balance"		=>		$depositos - $retiros
			);
		}
	}