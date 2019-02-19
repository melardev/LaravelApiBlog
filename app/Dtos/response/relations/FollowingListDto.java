package com.melardev.spring.twitterapi.dtos.response.relations;

import com.melardev.spring.twitterapi.dtos.PageMeta;
import com.melardev.spring.twitterapi.models.User;

import java.util.ArrayList;
import java.util.Collection;
import java.util.List;

public class FollowingListDto {
    private final PageMeta pageMeta;
    Collection<FollowingDto> users;

    public FollowingListDto(PageMeta pageMetaFollowing, List<FollowingDto> followingDtos) {
        this.pageMeta = pageMetaFollowing;
        this.users = followingDtos;
    }

    public static FollowingListDto build(PageMeta pageMetaFollowing, Collection<User> following) {
        List<FollowingDto> followingListDto = new ArrayList<>();
        following.forEach(u -> followingListDto.add(FollowingDto.build(u)));
        return new FollowingListDto(pageMetaFollowing, followingListDto);
    }

    public PageMeta getPageMeta() {
        return pageMeta;
    }

    public Collection<FollowingDto> getUsers() {
        return users;
    }

    public void setUsers(Collection<FollowingDto> users) {
        this.users = users;
    }
}
