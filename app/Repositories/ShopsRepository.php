<?php

namespace App\Repositories;

use App\Models\Shops;
use App\Models\Tags;
use App\Models\Task;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class shopsRepository
 * @package App\Repositories
 * @version May 21, 2023, 3:54 pm UTC
 */
class ShopsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'advertiser_id',
        'advertiser_name',
        'is_valid',
        'account_role',
        'created_at',
        'updated_at',
        'has_child'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function search($advertiserId, $name, $tagIds, $isValid, $parentId = 0, $paginate = true, $perPage = 15)
    {
        $query = $this->model->query()->with('tags:name');

        if ($advertiserId !== null) {
            $query->where('advertiser_id', $advertiserId);
        }

        if ($name !== null) {
            $query->where('advertiser_name', 'like', "%$name%");
        }

        if ($tagIds !== null) {
            $query->whereHas('tags', function ($query) use ($tagIds) {
                $query->whereIn('tags_id', $tagIds);
            });
        }

        if ($parentId !== null) {
            $query->where('parent_id', $parentId);
        }

        if ($isValid !== null) {
            $query->where('is_valid', $isValid);
        }

        if ($paginate) {
            return $query->paginate($perPage);
        }
        return $query->get();
    }

    public function shopAndTags($id): array
    {
        $shopTags = $this->model->query()->with('tags:name')->find($id);
        $useTags = [];
        if ($shopTags && $shopTags->tags) {
            foreach ($shopTags->tags as $tag) {
                $useTags[] = $tag['pivot']['tags_id'] ?? 0;
            }
        }
        $tags = Tags::all();
        $result = [];
        if ($tags->isNotEmpty()) {
            foreach ($tags as $key => $tag) {
                $result[$key]['title'] = $tag['name'];
                $result[$key]['value'] = $tag['id'];
                $result[$key]['key'] = $tag['id'];
                $result[$key]['checked'] = in_array($tag['id'], $useTags);
            }
        }
        return $result;
    }

    public function children($parentId = 0)
    {
        // 监控中的任务ID
        $advIds = Task::select('adv_id')->get()->pluck('adv_id')->flatten()->all();;

        $result = [];
        $field = ['id', 'parent_id', 'advertiser_id', 'advertiser_name'];
        $shops = Shops::where('parent_id', $parentId)->select($field)->get();
        if ($shops->isNotEmpty()) {
            foreach ($shops->toArray() as $key => $shop) {
                $result[$key] = $this->renameField($shop, $advIds);
            }
        }
        return $result;
    }

    /**
     * 映射字段
     *
     * @param $arr
     * @param $existIds
     * @return array
     */
    public function renameField($arr, $existIds): array
    {
        $data = [];
        $data['id'] = $arr['id'];
        $data['title'] = $arr['advertiser_name'];
        $data['value'] = $arr['advertiser_id'];
        $data['key'] = $arr['advertiser_id'];
        $data['disabled'] = in_array($arr['advertiser_id'], $existIds);
        return $data;
    }


    /**
     * Configure the Model
     **/
    public function model()
    {
        return Shops::class;
    }
}
