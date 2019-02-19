package com.melardev.spring.twitterapi.dtos.response.relations;

import com.melardev.spring.twitterapi.models.User;

public class FollowerDto extends AbstractRelationDto {

    public FollowerDto(String username, String imageUrl, String profileDescription) {
        super(username, imageUrl, profileDescription);
    }

    static FollowerDto build(User user) {
        return new FollowerDto(user.getUsername(), null, null);
    }
}
