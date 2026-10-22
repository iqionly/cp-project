<?php

namespace App\Services;

use App\Models\Contribution\FeeType;
use App\Models\Contribution\MemberFeeSetting;
use App\Models\MasterData\Member;

class FeeService
{
    protected $fee_type = null;

    public function getFeeActive()
    {
        if($this->fee_type) {
            return $this->fee_type;
        }
        // get event dues need to pay now
        return $this->fee_type = FeeType::query()
            ->where('is_active', true)
            ->first();
    }

    public function getMemberFee(Member $member)
    {
        if(!$this->getFeeActive()) {
            throw new \Exception("No active fee yet, please contact admin.");
        }

        $set = MemberFeeSetting::firstOrNew([
            'member_id' => $member->id,
            'fee_type_id' => $this->fee_type,
        ], [
            'amount' => $this->fee_type->default_amount,
            ]);

        return $set;
    }

}
