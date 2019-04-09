<?php

/* @nucleus/content/particle.html.twig */
class __TwigTemplate_0ea2e55b1058306411a7046e99370b28b101a554a6421626467598fac7888c97 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        try {            // line 2
            echo "    ";
            $context["id"] = $this->getAttribute(($context["segment"] ?? null), "id", []);
            // line 3
            echo "    ";
            if ( !($context["particle"] ?? null)) {
                // line 4
                echo "        ";
                if (($context["noConfig"] ?? null)) {
                    // line 5
                    echo "            ";
                    $context["enabled"] = true;
                    // line 6
                    echo "            ";
                    $context["particle"] = $this->getAttribute(($context["segment"] ?? null), "attributes", []);
                    // line 7
                    echo "        ";
                } else {
                    // line 8
                    echo "            ";
                    $context["enabled"] = $this->getAttribute($this->getAttribute(($context["gantry"] ?? null), "config", []), "get", [0 => (("particles." . $this->getAttribute(($context["segment"] ?? null), "subtype", [])) . ".enabled"), 1 => 1], "method");
                    // line 9
                    echo "            ";
                    $context["particle"] = $this->getAttribute($this->getAttribute(($context["gantry"] ?? null), "config", []), "getJoined", [0 => ("particles." . $this->getAttribute(($context["segment"] ?? null), "subtype", [])), 1 => $this->getAttribute(($context["segment"] ?? null), "attributes", [])], "method");
                    // line 10
                    echo "        ";
                }
                // line 11
                echo "    ";
            }
            // line 12
            echo "
    ";
            // line 13
            ob_start();
            // line 14
            echo "        ";
            if ((($context["enabled"] ?? null) && ((null === $this->getAttribute($this->getAttribute(($context["segment"] ?? null), "attributes", []), "enabled", [])) || $this->getAttribute($this->getAttribute(($context["segment"] ?? null), "attributes", []), "enabled", [])))) {
                // line 15
                echo "            ";
                $this->loadTemplate([0 => (("particles/" . $this->getAttribute(($context["segment"] ?? null), "subtype", [])) . ".html.twig"), 1 => (("@particles/" . $this->getAttribute(                // line 16
($context["segment"] ?? null), "subtype", [])) . ".html.twig"), 2 => "@nucleus/content/missing.html.twig"], "@nucleus/content/particle.html.twig", 15)->display($context);
                // line 18
                echo "        ";
            }
            // line 19
            echo "    ";
            $context["html"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
            // line 20
            echo "    ";
            $context["html"] = twig_trim_filter(($context["html"] ?? null));
            // line 21
            echo "
    ";
            // line 22
            $context["classes"] = twig_trim_filter(((( !($context["inContent"] ?? null)) ? ("g-content g-particle ") : ("g-particle ")) . twig_join_filter($this->getAttribute(($context["segment"] ?? null), "classes", []), " ")));
            // line 23
            if (($context["html"] ?? null)) {
                // line 24
                if ($this->getAttribute(($context["gantry"] ?? null), "debug", [])) {
                    echo "<!-- START PARTICLE ";
                    echo twig_escape_filter($this->env, ($context["id"] ?? null), "html", null, true);
                    echo " -->";
                }
                // line 25
                echo "
            ";
                // line 26
                if ( !(isset($context["ajax"]) || array_key_exists("ajax", $context))) {
                    echo "<div id=\"";
                    echo twig_escape_filter($this->env, ($context["id"] ?? null), "html", null, true);
                    echo "-particle\" class=\"";
                    echo twig_escape_filter($this->env, ($context["classes"] ?? null), "html", null, true);
                    echo "\">";
                }
                // line 27
                echo "            ";
                echo ($context["html"] ?? null);
                echo "
            ";
                // line 28
                if ( !(isset($context["ajax"]) || array_key_exists("ajax", $context))) {
                    echo "</div>";
                }
                // line 29
                echo "            ";
                if ($this->getAttribute(($context["gantry"] ?? null), "debug", [])) {
                    echo "<!-- END PARTICLE ";
                    echo twig_escape_filter($this->env, ($context["id"] ?? null), "html", null, true);
                    echo " -->";
                }
            }
        } catch (\Exception $e) {
            if ($context['gantry']->debug()) throw $e;
            GANTRY_DEBUGGER && method_exists('Gantry\Debugger', 'addException') && \Gantry\Debugger::addException($e);
            $context['e'] = $e;
            // line 33
            echo "    <div class=\"alert alert-error\"><strong>Error</strong> while rendering ";
            echo twig_escape_filter($this->env, $this->getAttribute(($context["segment"] ?? null), "subtype", []), "html", null, true);
            echo " particle.</div>
";
        }
    }

    public function getTemplateName()
    {
        return "@nucleus/content/particle.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  116 => 33,  104 => 29,  100 => 28,  95 => 27,  87 => 26,  84 => 25,  78 => 24,  76 => 23,  74 => 22,  71 => 21,  68 => 20,  65 => 19,  62 => 18,  60 => 16,  58 => 15,  55 => 14,  53 => 13,  50 => 12,  47 => 11,  44 => 10,  41 => 9,  38 => 8,  35 => 7,  32 => 6,  29 => 5,  26 => 4,  23 => 3,  20 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "@nucleus/content/particle.html.twig", "/home/dobrod/airbagua.com/www/media/gantry5/engines/nucleus/templates/content/particle.html.twig");
    }
}
