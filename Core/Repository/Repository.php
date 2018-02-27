<?php
namespace Core\Repository;
use Core\Traits\BuilderTrait;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Builder;
/**
 * Created by PhpStorm.
 * User: funmi ayinde
 * Date: 1/9/18
 * Time: 6:07 PM
 */
//use Illuminate\Database
abstract class Repository
{
    use BuilderTrait;
    protected $database;
    protected $model;
    protected $sort_prop = null;
    protected $sort_direction = 0; // ASC = 1, DESC =0

    abstract protected function get_model();

    final public function __construct(DatabaseManager $database)
    {
        $this->database = $database;
        $this->model = $this->get_model();
    }

    /*
     * Get all resources
     * **/
    public function get(array $options = null)
    {
        $query = $this->create_base_builder($options);
        return $query->get();
    }

    /*
     * this create  our custom build with core
     * it helps querying the db, it serves as
     * a base query, mind you apply_resouces_options
     * are limit,includes,page,filter_groups,sort
     * this is passed by the router
     * **/
    public function create_base_builder(array $options = [])
    {
        $query = $this->create_query_builder();
        $this->apply_resource_options($query, $options);
        if (empty($options['sort'])) {
            $this->default_sorting($query, $options);
        }
        return $query;
    }

    /*
     * this create a new query bulder
     * **/
    protected function create_query_builder()
    {
        return $this->model->newQuery();
    }

    /*
     * Get primary key name of the underlying model
     * **/
    protected function get_primary_key(Builder $query)
    {
        return $query->getModel()->getKeyName();
    }

    /*
     * Get a resource by its primary key
     * */
    public function get_by_id($id, array $options = [])
    {
        $query = $this->create_base_builder($options);
        return $query->find($id);
    }

    /*
     * Get a resources orderby by recentness
     * **/
    public function get_recent(array $options = [])
    {
        $query = $this->create_base_builder($options);
        $query->orderBy('created_at', 'DESC');
        return $query->get();
    }

    /*
     * Get all resources by a where clause ordered by
     * recentness
     * **/
    public function get_recent_where($column, $value, array $options = [])
    {
        $query = $this->create_base_builder($options);
        $query->orderBy('created_at', 'DESC');
        return $query->get();
    }

    /*
     * Get latest resource
     * **/
    public function get_latest(array $options = [])
    {
        $query = $this->create_base_builder($options);
        $query->orderBy($this->get_created_at_column(), 'DESC');
        return $query->first();
    }

    /*
     * Get latest resource by a where clause
     **/
    public function get_latest_by_where($column, $value, array $options = [])
    {
        $query = $this->create_base_builder($options);
        $query->orderBy($this->get_created_at_column(), "DESC");
        return $query->first();
    }

    /*
     * Get resource by where clause
     * **/
    public function get_where($column, $value, array $options = [])
    {
        $query = $this->create_base_builder($options);
        $query->where($column, $value);
        return $query->get();
    }

    /*
     * Get resource count by group by
     * */
    public function get_count_group_by($column, $threshold = null, array $options = [])
    {
        $query = $this->create_base_builder($options);
        if ($threshold !== null){
            $query->groupBy($column)->havingRaw('COUNT(*)' . $threshold);
        }else{
            $query->groupBy($column);
        }
        return $query;
    }

    /*
     * Get resources by multiple where clause
     * **/
    public function get_where_array(array $clause, array $options = [])
    {
        $query = $this->create_base_builder($options);
        $query->where($clause);
        return $query->get();
    }

    /*
     * Get resources where a column value exist in array
     */
    public function get_where_in($columns, array $value, array $options = [])
    {
        $query = $this->create_base_builder($options);
        $query->whereIn($columns, $value);
        return $query->get();
    }

    /*
     * Delete a resource by it's primary key,
     * It always Ids
     * */
    public function delete($id)
    {
        $query = $this->create_base_builder();
        $query->where($this->get_primary_key($query), $id);
        $query->delete();
    }

    /*
     * Delete a resource by where clause
     * */
    public function delete_where($column, $value)
    {
        $query = $this->create_base_builder();
        $query->where($column, $value);
        $query->delete();
    }

    /*
     * Get the name of the "created at" column
     * ***/
    protected function get_created_at_column()
    {
        $model = $this->model;
        return ($model::CREATED_AT) ? $model::CREATED_AT : "created_at";
    }

    /*
     * Order query by the specified sorting prop
     * **/
    protected function default_sorting(Builder $query, array $options = [])
    {
        if (isset($this->sort_prop)) {
            $direction = $this->sort_prop === 0 ? 'DESC' : 'ASC';
            $query->orderBy($this->sort_prop, $direction);
        }
    }

}