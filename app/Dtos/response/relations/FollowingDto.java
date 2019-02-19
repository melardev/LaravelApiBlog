package com.melardev.spring.twitterapi.dtos.response.relations;

import com.melardev.spring.twitterapi.models.User;

public class FollowingDto extends AbstractRelationDto {

    public FollowingDto(String username, String imageUrl, String profileDescription) {
        super(username, imageUrl, profileDescription);
    }

    static FollowingDto build(User user) {
        return new FollowingDto(user.getUsername(), null, null);
    }

}
