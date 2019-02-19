package com.melardev.spring.twitterapi.dtos.response.relations;

import com.melardev.spring.twitterapi.dtos.PageMeta;
import com.melardev.spring.twitterapi.models.User;

import java.util.ArrayList;
import java.util.Collection;
import java.util.List;

public class FollowersListDto {
    private final PageMeta pageMeta;
    Collection<FollowerDto> users;

    public FollowersListDto(PageMeta pageMeta, List<FollowerDto> followerDtos) {
        this.pageMeta = pageMeta;
        this.users = followerDtos;
    }

    public static FollowersListDto build(PageMeta pageMeta, Collection<User> followers) {
        List<FollowerDto> followerDtos = new ArrayList<>();
        followers.forEach(u -> followerDtos.add(FollowerDto.build(u)));
        return new FollowersListDto(pageMeta, followerDtos);
    }

    public PageMeta getPageMeta() {
        return pageMeta;
    }

    public Collection<FollowerDto> getUsers() {
        return users;
    }

    public void setUsers(Collection<FollowerDto> users) {
        this.users = users;
    }
}
