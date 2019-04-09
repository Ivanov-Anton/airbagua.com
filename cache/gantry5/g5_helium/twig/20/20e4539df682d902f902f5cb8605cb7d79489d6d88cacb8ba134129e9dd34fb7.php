<?php

/* component.html.twig */
class __TwigTemplate_a5d7b310a1ee5b08f2295ab7f30312a2f89d998196415e4a7f0544184731b48c extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("partials/page.html.twig", "component.html.twig", 1);
        $this->blocks = array(
            'page_body' => array($this, 'block_page_body'),
            'page_layout' => array($this, 'block_page_layout'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "partials/page.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_page_body($context, array $blocks = array())
    {
        // line 4
        echo "<body";
        echo $this->getAttribute($this->getAttribute(($context["gantry"] ?? null), "page", array()), "bodyAttributes", array(0 => array("class" => array(0 => ($context["offcanvas_position"] ?? null), 1 => $this->getAttribute($this->getAttribute(($context["gantry"] ?? null), "page", array()), "preset", array()), 2 => ("g-style-" . $this->getAttribute($this->getAttribute(($context["gantry"] ?? null), "theme", array()), "preset", array()))))), "method");
        echo ">
    ";
        // line 5
        echo $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute(($context["gantry"] ?? null), "config", array()), "page", array()), "body", array()), "body_top", array());
        echo "
    ";
        // line 6
        echo ($context["page_top"] ?? null);
        echo "
    ";
        // line 7
        echo ($context["page_layout"] ?? null);
        echo "
    ";
        // line 8
        echo ($context["page_bottom"] ?? null);
        echo "
    ";
        // line 9
        echo ($context["page_footer"] ?? null);
        echo "
    ";
        // line 10
        echo $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute(($context["gantry"] ?? null), "config", array()), "page", array()), "body", array()), "body_bottom", array());
        echo "
    </body>";
    }

    // line 14
    public function block_page_layout($context, array $blocks = array())
    {
        // line 15
        echo "    ";
        $this->getAttribute($this->getAttribute(($context["gantry"] ?? null), "theme", array()), "joomla", array(0 => true), "method");
        // line 16
        echo "    <div class=\"platform-content row-fluid\">
        <div class=\"span12\">
            <jdoc:include type=\"message\" />
            <jdoc:include type=\"component\" />
        </div>
    </div>
";
    }

    public function getTemplateName()
    {
        return "component.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  69 => 16,  66 => 15,  63 => 14,  57 => 10,  53 => 9,  49 => 8,  45 => 7,  41 => 6,  37 => 5,  32 => 4,  29 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "component.html.twig", "/home/dobrod/airbagua.com/www/media/gantry5/engines/nucleus/twig/component.html.twig");
    }
}
