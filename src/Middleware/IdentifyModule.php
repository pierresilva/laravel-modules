<?php

namespace pierresilva\Modules\Middleware;

use pierresilva\Modules\RepositoryManager;
use Closure;

class IdentifyModule
{
    /**
     * @var pierresilva\Modules
     */
    protected $module;

    /**
     * Create a new IdentifyModule instance.
     *
     * @param pierresilva\Modules $module
     */
    public function __construct(RepositoryManager $module)
    {
        $this->module = $module;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $slug = null)
    {
        $request->session()->flash('module', $this->module->where('slug', $slug));

        return $next($request);
    }
}
