<?php
/**
 * Empire view controller.
 *
 * @package Bengine
 * @subpackage Empire
 * @copyright Copyright protected by / Urheberrechtlich geschützt durch "Sebastian Noll" <snoll@4ym.org>
 */

class Bengine_Empire_Controller_View extends Bengine_Game_Controller_Abstract
{
	public function indexAction()
	{
		Core::getLanguage()->load(array("info", "buildings"));

		/* @var Bengine_Game_Model_Collection_Planet $planets */
		$planets = Game::getCollection("game/planet");
		$planets->addUserFilter(Core::getUser()->get("userid"));
		$planets->load();
		Core::getTemplate()->addLoop("planets", $planets);

		/* @var Bengine_Game_Model_Collection_Construction $buildings */
		$buildings = Game::getCollection("game/construction");
		$buildings->addTypeFilter(1);
		Core::getTemplate()->addLoop("buildings", $buildings);

		/* @var Bengine_Game_Model_Collection_Construction $ships */
		$ships = Game::getCollection("game/construction");
		$ships->addTypeFilter(3);
		Core::getTemplate()->addLoop("ships", $ships);

		/* @var Bengine_Game_Model_Collection_Construction $defense */
		$defense = Game::getCollection("game/construction");
		$defense->addTypeFilter(4);
		Core::getTemplate()->addLoop("defense", $defense);
		return $this;
	}
}