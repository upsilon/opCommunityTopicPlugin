<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Community Event Search Form
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Yoichiro SAKURAI <saku2saku@gmail.com>
 */

class CommunityEventSearchForm extends PluginCommunityEventFormFilter
{
  public function __construct($defaults = array(), $options = array(), $CSRFSecret = null)
  {
    return parent::__construct($defaults, $options, false);
  }

  public function configure()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'name'                  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'body'                  => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'id' => new sfValidatorPass(),
      'name' => new sfValidatorPass(),
      'body' => new sfValidatorPass(),
    ));

    $this->widgetSchema->setLabel('id', sfContext::getInstance()->getI18N()->__('Event ID'));
    $this->widgetSchema->setLabel('name', sfContext::getInstance()->getI18N()->__('Event Title'));
    $this->widgetSchema->setLabel('body', sfContext::getInstance()->getI18N()->__('Event Description'));

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    $this->widgetSchema->setNameFormat('communityEvent[%s]');
    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('form_community');
  }

  public function getQuery() {
    $parameter = $this->getTaintedValues();
    $community_event_id = $parameter['id']['text'];
    $name = $parameter['name']['text'];
    $body = $parameter['body']['text'];
    $query = Doctrine_Query::create()->from('CommunityEvent');
    if (!empty($community_event_id)) $query->andWhere('id = ?', $community_event_id);
    if (!empty($name))
    {
      if (method_exists($query, 'andWhereLike'))
      {
        $query->andWhereLike('name', $name);
      }
      else
      {
        $query->andWhere('name LIKE ?', '%'.$name.'%');
      }
    }
    if (!empty($body))
    {
      if (method_exists($query, 'andWhereLike'))
      {
        $query->andWhereLike('body', $body);
      }
      else
      {
        $query->andWhere('body LIKE ?', '%'.$body.'%');
      }
    }

    return $query;
  }
}
