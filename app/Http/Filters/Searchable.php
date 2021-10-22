<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

trait Searchable
{
    protected Builder $builder;

    protected float $lat;
    protected float $lon;
    protected float $lat2;
    protected float $lon2;

    protected float $radius;
    protected float $width;
    protected float $height;

    protected string $metric = 'km';

    public function __set($var, $value)
    {
        if (property_exists($this, $var))
            $this->$var = $value;
    }

    public function search(): void
    {
        $logic = $this->searchLogic();

        switch ($logic) {
            case SearchLogicEnum::CIRCLE:
                $this->inCircle();
                break;

            case SearchLogicEnum::RECTANGLE:
                $this->inRectangle();
                break;

            case SearchLogicEnum::RECTANGLE_BY_POINT:
                $this->inRectangleByPoint();
                break;

            case SearchLogicEnum::NONE:
            default:
                break;
        }
    }

    public function inCircle(): void
    {
        $this->addDistanceToPoint($this->lat, $this->lon);
        $this->builder->having('distance', "<", $this->radius);
    }

    public function inRectangle(): void
    {
        $centerLat = ($this->lat + $this->lat2) / 2;
        $centerLon = ($this->lon + $this->lon2) / 2;

        $this->addDistanceToPoint($centerLat, $centerLon);
        $this->builder
            ->where(function($query) {
                $query->where('latitude', '>=', $this->lat)
                    ->where('latitude', '<=', $this->lat2);
            })
            ->where(function($query) {
                $query->where('longitude', '>=', $this->lon)
                    ->where('longitude', '<=', $this->lon2);
            });
    }

    public function inRectangleByPoint(): void
    {
        $metric = $this->searchMetric();
        $pi180 = M_PI * 180;

        $dLat = $this->height / 2 / $metric * $pi180;
        $dLon = $this->width / 2 / ($metric * cos(M_PI * $this->lat/180)) * $pi180;

        $this->lat -= $dLat;
        $this->lon -= $dLon;
        $this->lat2 += $dLat;
        $this->lon2 += $dLon;

        $this->inRectangle();
    }

    public function searchLogic() : int
    {
        if (!isset($this->lat, $this->lon))
            return SearchLogicEnum::NONE;

        if (isset($this->radius))
            return SearchLogicEnum::CIRCLE;

        if (isset($this->lat2, $this->lon2))
            return SearchLogicEnum::RECTANGLE;

        if (isset($this->width, $this->height))
            return SearchLogicEnum::RECTANGLE_BY_POINT;

        return SearchLogicEnum::NONE;
    }

    private function searchMetric() : int
    {
        if (is_numeric($this->metric))
            return intval($this->metric);

        switch ($this->metric) {
            case 'm':
                return SearchMetricEnum::METERS;
            case 'mile':
                return SearchMetricEnum::MILES;
            case 'km':
            default:
                return SearchMetricEnum::KILOMETERS;
        }
    }

    private function addDistanceToPoint(float $latitude, float $longitude): void
    {
        $this->builder->selectRaw('( ? * acos( cos( radians(?) ) *
                           cos( radians( latitude ) )
                           * cos( radians( longitude ) - radians(?)
                           ) + sin( radians(?) ) *
                           sin( radians( latitude ) ) )
                         ) AS distance', [$this->searchMetric(), $latitude, $longitude, $latitude]);
    }
}
