---
id: project-laraddress
blueprint: project
title: 'laraddress: Federated Address Book'
tagline: 'The first concrete step in leaving Facebook: a federated, user-owned address book.'
date: '2019-01-01'
status: archived
summary: 'Laravel app for collecting contact info before leaving Facebook — a replacement for the centralized social graph with verified contact cards and selective visibility.'
links:
  -
    type: link
    enabled: true
    label: 'GitHub'
    url: 'https://github.com/zaskoda/laraddress'
    icon: github
  -
    type: link
    enabled: true
    label: 'Leaving Facebook'
    url: 'https://zaskoda.com/2019/01/01/leaving-facebook-why/'
    icon: external
project_type:
  - software
  - open-source
context:
  - personal
---

laraddress is a Laravel application I built for collecting contact information from my Facebook friends b— and, underneath that, a replacement for the centralized social graph itself. Friends create verified contact cards and choose what each group can see: address, email, birthday, social accounts. The planned federation was the real point: if two friends each ran their own copy, the instances would sync over an API, so your network would belong to you and the people in it, not to a company sitting in the middle.

