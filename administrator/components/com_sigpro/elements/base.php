<?php
/**
 * @version    3.6.x
 * @package    Simple Image Gallery Pro
 * @author     JoomlaWorks - https://www.joomlaworks.net
 * @copyright  Copyright (c) 2006 - 2018 JoomlaWorks Ltd. All rights reserved.
 * @license    https://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die;

if (version_compare(JVERSION, '3.6.2', 'ge'))
{
	jimport('joomla.form.formfield');
	class JWElement extends JFormField
	{
		function getInput()
		{
			return $this->fetchElement($this->name, $this->value, $this->element, $this->options['control']);
		}

		function getLabel()
		{
			if (method_exists($this, 'fetchTooltip'))
			{
				return $this->fetchTooltip($this->element['label'], $this->description, $this->element, $this->options['control'], $this->element['name'] = '');
			}
			else
			{
				return parent::getLabel();
			}

		}

		function render($layoutId, $data = array())
		{
			return $this->getInput();
		}

	}
}
else if (version_compare(JVERSION, '1.6.0', 'ge'))
{
	jimport('joomla.form.formfield');
	class JWElement extends JFormField
	{
		function getInput()
		{
			return $this->fetchElement($this->name, $this->value, $this->element, $this->options['control']);
		}

		function getLabel()
		{
			if (method_exists($this, 'fetchTooltip'))
			{
				return $this->fetchTooltip($this->element['label'], $this->description, $this->element, $this->options['control'], $this->element['name'] = '');
			}
			else
			{
				return parent::getLabel();
			}

		}

		function render()
		{
			return $this->getInput();
		}

	}

}
else
{
	jimport('joomla.html.parameter.element');
	class JWElement extends JElement
	{
	}

}
